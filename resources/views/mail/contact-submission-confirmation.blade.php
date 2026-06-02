<p>Bonjour,</p>

<p>Nous avons bien reçu votre message envoyé depuis le site.</p>

<p><strong>Votre email :</strong> {{ $submission->email }}</p>
@if ($submission->name)
    <p><strong>Nom :</strong> {{ $submission->name }}</p>
@endif
@if ($submission->subject)
    <p><strong>Sujet :</strong> {{ $submission->subject }}</p>
@endif
<p><strong>Message :</strong></p>
<p>{{ $submission->message }}</p>

<p>Nous revenons vers vous rapidement.</p>
