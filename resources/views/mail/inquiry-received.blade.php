<x-mail::message>
# New inquiry from the website

<x-mail::table>
| | |
|:--|:--|
| **Name** | {{ $inquiry->name }} |
| **Email** | {{ $inquiry->email }} |
| **Phone** | {{ $inquiry->phone ?: '—' }} |
| **Company** | {{ $inquiry->company ?: '—' }} |
| **Service** | {{ $inquiry->service?->title ?: '—' }} |
</x-mail::table>

**Message**

{{ $inquiry->message }}

<x-mail::button :url="$inboxUrl">
Open the inbox
</x-mail::button>

Reply to this email to answer {{ $inquiry->name }} directly.
</x-mail::message>
