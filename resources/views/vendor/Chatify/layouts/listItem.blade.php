{{-- -------------------- Saved Messages -------------------- --}}
@if ($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if ($get == 'users' && !!$lastMessage)
    <?php
    $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
    $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8') . '..' : $lastMessageBody;
    ?>
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td style="position: relative">
                @if ($user->active_status)
                    <span class="activeStatus"></span>
                @endif
                <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}');">
                </div>
            </td>
            {{-- center side --}}
            <td class="flex">

                <p data-id="{{ $user->id }}" data-type="use">

                    {{ strlen($user->name) > 12 ? trim(substr($user->name, 0, 12)) . '..' : $user->name }}

                    <span class="contact-item-time" data-time="{{ $lastMessage->created_at }}">
                        {{ $lastMessage->timeAgo }}

                        @if ($user->hak_akses == 'admin' || $user->hak_akses == 'Administrator')
                            <i style="margin-inline-end: 6px; font-size: 0.75rem; color: #8b5cf6;" class="fas fa-check-circle text-purple-500 text-xs ml-1 me-2" title="Verified Admin"></i>
                        @endif
                    </span>



                </p>
                <span>
                    {{-- Last Message user indicator --}}
                    {!! $lastMessage->from_id == Auth::user()->id ? '<span class="lastMessageIndicator">You :</span>' : '' !!}
                    {{-- Last message body --}}
                    @if ($lastMessage->attachment == null)
                        {!! $lastMessageBody !!}
                    @else
                        <span class="fas fa-file"></span> Attachment
                    @endif
                </span>
                {{-- New messages counter --}}
                {!! $unseenCounter > 0 ? '<b>' . $unseenCounter . '</b>' : '' !!}
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if ($get == 'search_item')
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}');">
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->name) > 12 ? trim(substr($user->name, 0, 12)) . '..' : $user->name }}
                    @if ($user->hak_akses == 'admin' or $user->hak_akses == 'Administrator')
                        <span class="ml-1 text-xs text-red-500 font-semibold">[Admin]</span>
                    @endif
                    {{-- {{ $user->hak_akses }} --}}
                </p>
            </td>

        </tr>
    </table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if ($get == 'sharedPhoto')
    <div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif
