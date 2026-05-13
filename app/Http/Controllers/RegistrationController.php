<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendRegistrationVerificationRequest;
use App\Http\Requests\SendRegistrationVerificationRequest;
use App\Http\Requests\VerifyRegistrationCodeRequest;
use App\Mail\RegistrationVerificationCodeMail;
use App\Models\Event;
use App\Models\RegistrationVerificationCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly EventRegistrantController $eventRegistrants,
    ) {}

    public function resendVerificationCode(ResendRegistrationVerificationRequest $request): JsonResponse
    {
        $verification = RegistrationVerificationCode::query()->findOrFail($request->integer('verification_id'));

        if ($verification->status === 'verified') {
            throw ValidationException::withMessages([
                'verification_id' => 'Registration is already verified. No resend is needed.',
            ]);
        }

        $event = Event::query()->findOrFail($verification->event_id);
        $payload = (array) $verification->payload;
        $fullName = trim(((string) ($payload['first_name'] ?? '')).' '.((string) ($payload['last_name'] ?? '')));
        $email = Str::lower(trim((string) $verification->email));

        $code = $this->generateVerificationCode();
        $expiresAt = now()->addMinutes(10);

        $this->assertRealMailerConfiguration();

        Log::info('Registration verification resend attempt started.', [
            'verification_id' => $verification->id,
            'event_id' => $event->event_id,
            'recipient_email' => $email,
            'mailer' => (string) config('mail.default'),
        ]);

        try {
            Mail::to($email)->send(new RegistrationVerificationCodeMail(
                eventName: $event->event_name,
                fullName: $fullName !== '' ? $fullName : 'Participant',
                code: $code,
                expiresAt: $expiresAt->format('M d, Y h:i A')
            ));
        } catch (Throwable $exception) {
            Log::error('Registration verification resend failed.', [
                'verification_id' => $verification->id,
                'recipient_email' => $email,
                'mailer' => (string) config('mail.default'),
                'error' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'We could not resend the verification email right now. Please try again.',
            ]);
        }

        RegistrationVerificationCode::query()
            ->where('email', $email)
            ->where('event_id', $verification->event_id)
            ->where('status', 'pending')
            ->where('id', '!=', $verification->id)
            ->update(['status' => 'expired']);

        $verification->forceFill([
            'verification_code_hash' => Hash::make($code),
            'status' => 'pending',
            'attempts' => 0,
            'expires_at' => $expiresAt,
            'verified_at' => null,
        ])->save();

        Log::info('Registration verification resend successful.', [
            'verification_id' => $verification->id,
            'recipient_email' => $email,
            'mailer' => (string) config('mail.default'),
        ]);

        return response()->json([
            'message' => 'A new verification code was sent to your email.',
            'data' => [
                'verification_id' => $verification->id,
                'expires_at' => $expiresAt->toIso8601String(),
                'email_masked' => $this->maskEmail($email),
            ],
        ]);
    }

    public function sendVerification(SendRegistrationVerificationRequest $request): JsonResponse
    {
        $event = Event::query()->findOrFail($request->integer('event_id'));
        $isConferenceEvent = (string) $event->event_type === 'Conference';

        if (Carbon::parse($event->event_date)->isPast()) {
            throw ValidationException::withMessages([
                'event_id' => 'Registration for this event is already closed.',
            ]);
        }

        $email = Str::lower(trim((string) $request->input('email')));

        $existingUser = User::query()->where('email', $email)->first();
        if ($existingUser !== null && $existingUser->role !== 'participant') {
            throw ValidationException::withMessages([
                'email' => 'This email is already used by a non-participant account.',
            ]);
        }

        $activeEventRegistrant = DB::table('event_registrants')
            ->where('event_id', $event->event_id)
            ->whereRaw('LOWER(email) = ?', [$email])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($activeEventRegistrant) {
            throw ValidationException::withMessages([
                'email' => 'You are already registered for this event.',
            ]);
        }

        $legacyActiveRegistration = DB::table('registrations')
            ->join('users', 'users.id', '=', 'registrations.user_id')
            ->where('registrations.event_id', $event->event_id)
            ->whereRaw('LOWER(users.email) = ?', [$email])
            ->whereNotNull('registrations.user_id')
            ->whereNotIn('registrations.status', ['cancelled', 'rejected'])
            ->exists();

        if ($legacyActiveRegistration) {
            throw ValidationException::withMessages([
                'email' => 'You are already registered for this event.',
            ]);
        }

        $paperTempPath = null;
        if ($isConferenceEvent) {
            if (! $request->hasFile('paper_file')) {
                throw ValidationException::withMessages([
                    'paper_file' => 'A research paper PDF is required for conference registrations.',
                ]);
            }

            $paperTempPath = $request->file('paper_file')->store('pending-papers', 'public');
        }

        RegistrationVerificationCode::query()
            ->where('email', $email)
            ->where('event_id', $event->event_id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $code = $this->generateVerificationCode();
        $expiresAt = now()->addMinutes(10);

        $verification = RegistrationVerificationCode::query()->create([
            'event_id' => $event->event_id,
            'email' => $email,
            'verification_code_hash' => Hash::make($code),
            'payload' => [
                'first_name' => trim((string) $request->input('first_name')),
                'last_name' => trim((string) $request->input('last_name')),
                'email' => $email,
                'event_type' => (string) $event->event_type,
                'school_university' => trim((string) $request->input('region')),
                'user_type' => trim((string) $request->input('school_from')),
                'participant_role' => trim((string) $request->input('school_level')),
                'region' => trim((string) $request->input('region')),
                'school_from' => trim((string) $request->input('school_from')),
                'school_level' => trim((string) $request->input('school_level')),
                'school_affiliation' => trim((string) $request->input('region')),
            ],
            'paper_temp_path' => $paperTempPath,
            'status' => 'pending',
            'expires_at' => $expiresAt,
        ]);

        $this->assertRealMailerConfiguration();

        Log::info('Registration verification mail send attempt started.', [
            'verification_id' => $verification->id,
            'event_id' => $event->event_id,
            'recipient_email' => $email,
            'mailer' => (string) config('mail.default'),
        ]);

        try {
            Mail::to($email)->send(new RegistrationVerificationCodeMail(
                eventName: $event->event_name,
                fullName: $verification->payload['first_name'].' '.$verification->payload['last_name'],
                code: $code,
                expiresAt: $expiresAt->format('M d, Y h:i A')
            ));

            Log::info('Registration verification mail sent successfully.', [
                'verification_id' => $verification->id,
                'recipient_email' => $email,
                'mailer' => (string) config('mail.default'),
            ]);
        } catch (Throwable $exception) {
            $verification->update(['status' => 'failed']);

            Log::error('Registration verification mail sending failed.', [
                'verification_id' => $verification->id,
                'recipient_email' => $email,
                'mailer' => (string) config('mail.default'),
                'error' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'We could not send the verification email right now. Please try again.',
            ]);
        }

        return response()->json([
            'message' => 'Verification code sent successfully.',
            'data' => [
                'verification_id' => $verification->id,
                'expires_at' => $expiresAt->toIso8601String(),
                'email_masked' => $this->maskEmail($email),
            ],
        ]);
    }

    public function verifyCodeAndFinalize(VerifyRegistrationCodeRequest $request): JsonResponse
    {
        $verification = RegistrationVerificationCode::query()->findOrFail($request->integer('verification_id'));

        if ($verification->status !== 'pending') {
            throw ValidationException::withMessages([
                'code' => 'This verification session is no longer active. Please register again.',
            ]);
        }

        $latestPendingId = RegistrationVerificationCode::query()
            ->where('email', $verification->email)
            ->where('event_id', $verification->event_id)
            ->where('status', 'pending')
            ->max('id');

        if ($latestPendingId !== null && (int) $latestPendingId !== (int) $verification->id) {
            throw ValidationException::withMessages([
                'code' => 'A newer verification code was issued. Please use the latest code sent to your email.',
            ]);
        }

        if ($verification->expires_at->isPast()) {
            $verification->update(['status' => 'expired']);

            throw ValidationException::withMessages([
                'code' => 'Verification code expired. Please request a new code.',
            ]);
        }

        $inputCode = strtoupper(trim((string) $request->input('code')));
        if (! Hash::check($inputCode, $verification->verification_code_hash)) {
            $attempts = $verification->attempts + 1;
            $verification->update([
                'attempts' => $attempts,
                'status' => $attempts >= 5 ? 'failed' : 'pending',
            ]);

            throw ValidationException::withMessages([
                'code' => $attempts >= 5
                  ? 'Too many invalid attempts. Please restart registration.'
                  : 'Invalid verification code. Please try again.',
            ]);
        }

        $payload = (array) $verification->payload;
        $event = Event::query()->findOrFail($verification->event_id);
        $isConferenceEvent = (string) $event->event_type === 'Conference';

        if ($isConferenceEvent && empty($verification->paper_temp_path)) {
            throw ValidationException::withMessages([
                'code' => 'Conference registration requires a research paper PDF upload.',
            ]);
        }

        $result = $this->eventRegistrants->completeVerifiedEnrollment(
            $verification,
            $payload,
            $event,
            $isConferenceEvent,
        );

        return response()->json([
            'message' => 'Registration submitted successfully and is pending admin approval.',
            'data' => $result,
        ]);
    }

    private function generateVerificationCode(): string
    {
        return strtoupper(Str::random(6));
    }

    private function maskEmail(string $email): string
    {
        [$localPart, $domainPart] = array_pad(explode('@', $email, 2), 2, '');

        if ($localPart === '' || $domainPart === '') {
            return $email;
        }

        if (strlen($localPart) <= 2) {
            return str_repeat('*', strlen($localPart)).'@'.$domainPart;
        }

        return substr($localPart, 0, 2).str_repeat('*', max(strlen($localPart) - 2, 1)).'@'.$domainPart;
    }

    private function assertRealMailerConfiguration(): void
    {
        $mailer = (string) config('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            throw ValidationException::withMessages([
                'email' => 'Email delivery is disabled because MAIL_MAILER is set to a non-delivery driver. Configure SMTP in .env to send real verification emails.',
            ]);
        }

        if ($mailer !== 'smtp') {
            return;
        }

        $host = strtolower((string) config('mail.mailers.smtp.host'));
        $port = (string) config('mail.mailers.smtp.port');
        $username = trim((string) config('mail.mailers.smtp.username'));
        $password = trim((string) config('mail.mailers.smtp.password'));
        $fromAddress = trim((string) config('mail.from.address'));

        $isLocalHost = in_array($host, ['', '127.0.0.1', 'localhost'], true);
        $missingAuth = ($username === '' || $password === '');
        $invalidFrom = ($fromAddress === '' || $fromAddress === 'hello@example.com');
        $usingPlaceholders = str_starts_with($username, 'your_')
          || str_starts_with($password, 'your_')
          || $username === 'your_gmail_address@gmail.com';

        if ($isLocalHost || $missingAuth || $invalidFrom || $port === '' || $usingPlaceholders) {
            throw ValidationException::withMessages([
                'email' => 'SMTP is not fully configured. Set MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, and MAIL_FROM_ADDRESS in .env.',
            ]);
        }
    }
}
