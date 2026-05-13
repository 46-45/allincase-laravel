<!-- Back to top -->
<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

<!-- Bootstrap js-->
<script src="{{ asset('build/assets/plugins/bootstrap/popper.min.js') }}?v=<?php echo time(); ?>"></script>
<script src="{{ asset('build/assets/plugins/bootstrap/js/bootstrap.min.js') }}?v=<?php echo time(); ?>"></script>

<!--Sidemenu js-->
<script src="{{ asset('build/assets/plugins/sidemenu/sidemenu.js') }}?v=<?php echo time(); ?>"></script>

<!-- P-scroll js-->
<script src="{{ asset('build/assets/plugins/p-scrollbar/p-scrollbar.js') }}?v=<?php echo time(); ?>"></script>
<script src="{{ asset('build/assets/plugins/p-scrollbar/p-scroll1.js') }}?v=<?php echo time(); ?>"></script>

<!-- Select2 js -->
<script src="{{ asset('build/assets/plugins/select2/select2.full.min.js') }}?v=<?php echo time(); ?>"></script>

<!--INTERNAL RATING js -->
<script src="{{ asset('build/assets/plugins/ratings/jquerystarrating.js') }}?v=<?php echo time(); ?>"></script>

<!--INTERNAL Toastr js -->
<script src="{{ asset('build/assets/plugins/toastr/toastr.min.js') }}?v=<?php echo time(); ?>"></script>

@yield('scripts')

<!-- Custom html js-->
@vite(['resources/assets/js/custom.js'])
@if(setting('liveChatHidden') == "false")
    <script domainName='{{ url('') }}' wsPort="{{ setting('liveChatPort') }}" src="{{ asset('build/assets/plugins/livechat/web-socket.js') }}"></script>
    <script>


        // Increment the count of open tabs/windows when a new one is opened
        let loadedEventTriger = false
            window.addEventListener('load', function() {
                loadedEventTriger = true
                // To check the tabs Open
                let tabsOpen = localStorage.getItem('tabsOpen');
                tabsOpen = tabsOpen ? parseInt(tabsOpen) + 1 : 1;
                localStorage.setItem('tabsOpen', tabsOpen.toString());
            });


            let OpenUserInfo = []

            // To Romove the last user from the Setting
            function beforeUnloadHandler(e) {
                if (loadedEventTriger) {
                    let tabsOpen = localStorage.getItem('tabsOpen');
                    if (tabsOpen) {
                        tabsOpen = parseInt(tabsOpen) - 1;
                        if (tabsOpen <= 0) {
                            let data = {
                                users: "",
                                onlineUserUpdated: true
                            }
                            $.ajax({
                                type: "post",
                                url: '{{ route('admin.onlineUsersSave') }}',
                                data: data,
                                success: function(data) {

                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                            });
                            localStorage.removeItem('tabsOpen');
                        }
                    }
                }
            }

            //LiveChat  Online users induction logic
            let onlineUsersFindChannel = Echo.join('agentMessaging')
            onlineUsersFindChannel.here((users) => {
                let data = {
                    users: JSON.stringify(users),
                    onlineUserUpdated: true
                }

                $.ajax({
                    type: "post",
                    url: '{{ route('admin.onlineUsersSave') }}',
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                // adding the window close alert
                if (users.length == 1 && users[0].id == "{{ Auth::user()->id }}") {
                    OpenUserInfo = users
                    window.addEventListener('beforeunload', beforeUnloadHandler);
                }
            })
            onlineUsersFindChannel.joining((user) => {
                let data = {
                    users: JSON.stringify(Object.values(onlineUsersFindChannel.subscription.members
                        .members)),
                    onlineUserUpdated: true
                }
                $.ajax({
                    type: "post",
                    url: '{{ route('admin.onlineUsersSave') }}',
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                // removing the window close alert
                if (JSON.parse(data.users).length > 1) {
                    window.removeEventListener('beforeunload', beforeUnloadHandler);
                }
            })
            onlineUsersFindChannel.leaving((user) => {
                let data = {
                    users: JSON.stringify(Object.values(onlineUsersFindChannel.subscription.members
                        .members)),
                    onlineUserUpdated: true
                }
                $.ajax({
                    type: "post",
                    url: '{{ route('admin.onlineUsersSave') }}',
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                // removing the window close alert
                if (JSON.parse(data.users).length > 1) {
                    window.removeEventListener('beforeunload', beforeUnloadHandler);
                } else {
                    OpenUserInfo = JSON.parse(data.users)
                    window.addEventListener('beforeunload', beforeUnloadHandler);
                }
            })

            const vibrateTwoTimes = () => {
                if ('vibrate' in navigator) {
                    try {
                        window.navigator.vibrate([300, 100, 300]);
                    } catch (error) {
                        console.error('Vibration API error:', error);
                    }
                }
            }

            // Variables
            var SITEURL = '{{ url('') }}';
            var notificationsSounds = "{{ setting('notificationsSounds') }}"
            var newMessageWebNot = "{{ setting('newMessageWebNot') }}"
            var newMessageSound = "{{ setting('newMessageSound') }}"
            var newChatRequestSound = "{{ setting('newChatRequestSound') }}"
            var newChatRequestWebNot = "{{ setting('newChatRequestWebNot') }}"
            var liveChatCustomers = {!! json_encode(liveChatCustomers()) !!}
            const autID_G = '{{ Auth::user()->id }}'
            var notificationType = "{{ setting('notificationType') }}"

            // Live Chat Global Web Notifications
            window.newMessageSoundCurrentAudio = "";
            let intervalId

            // For the Live Chat Notification
            Echo.channel('liveChat').listen('ChatMessageEvent', (socket) => {

                // For the New Chat request
                if (socket.message == "newUser" && parseInt(notificationsSounds) && parseInt(
                        newChatRequestWebNot)) {
                    // For the Window notification
                    if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }

                    function notify() {
                        vibrateTwoTimes();
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.userName, {
                                body: socket.message,
                                icon: `${SITEURL}/uploads/profile/user-profile.png`,
                                vibration: 5000,
                                data: {
                                    link: `${SITEURL}/admin/livechat`
                                }
                            });
                        })

                    }

                    // Stop the current audio if it exists
                    if (newMessageSoundCurrentAudio) {
                        newMessageSoundCurrentAudio.pause();
                        newMessageSoundCurrentAudio.currentTime = 0;
                    }

                    // Create a new audio element
                    let audioElement = document.createElement('audio');
                    audioElement.id = "audioPlayer";
                    audioElement.innerHTML = `
                        <source src="{{ url('') }}/uploads/livechatsounds/${newChatRequestSound}">
                    `;

                    // Play the new audio
                    if (audioElement.paused) {
                        audioElement.play();
                        vibrateTwoTimes();
                    }
                    newMessageSoundCurrentAudio = audioElement;
                    newMessageSoundCurrentAudio.__proto__.cusumerId = socket.id


                    // Function to check localStorage value and play or stop the audio
                    if (notificationType == "Loop") {
                        function checkAndPlaySound() {
                            if (localStorage.livechatCustomer == newMessageSoundCurrentAudio.__proto__
                                .cusumerId) {
                                if (!audioElement.paused) {
                                    audioElement.pause();
                                }
                                clearInterval(intervalId);
                            } else {
                                if (audioElement.paused) {
                                    audioElement.play();
                                    vibrateTwoTimes();
                                }
                            }
                        }

                        let intervalId = setInterval(checkAndPlaySound, 1000);
                    }
                }

                // For the New message
                if (parseInt(notificationsSounds) && parseInt(newMessageWebNot)) {
                    liveChatCustomers.forEach((ele) => {
                        if (ele.id == socket.id && !socket.agentInfo) {
                            try {
                                if (ele.engage_conversation == null && socket.message || (JSON.parse(ele.engage_conversation) && JSON.parse(ele.engage_conversation).some(item => item.id == autID_G))) {

                                    if ("Notification" in window) {
                                        if (Notification.permission === "granted") {
                                            notify();
                                        } else {
                                            Notification.requestPermission().then(res => {
                                                if (res === "granted") {
                                                    notify();
                                                } else {
                                                    console.error(
                                                        "Did not receive permission for notifications"
                                                    );
                                                }
                                            })
                                        }
                                    } else {
                                        console.error("Browser does not support notifications");
                                    }

                                    function notify() {
                                        vibrateTwoTimes();
                                        navigator.serviceWorker.ready.then(function(registration) {
                                            registration.showNotification(socket.userName, {
                                                body: socket.message,
                                                icon: `${SITEURL}/uploads/profile/user-profile.png`,
                                                vibration: 5000,
                                                data: {
                                                    link: `${SITEURL}/admin/myopened`
                                                }
                                            });
                                        })
                                    }

                                    // Stop the current audio if it exists
                                    if (newMessageSoundCurrentAudio) {
                                        newMessageSoundCurrentAudio.pause();
                                        newMessageSoundCurrentAudio.currentTime = 0;
                                    }

                                    // Create a new audio element
                                    let audioElement = document.createElement('audio');
                                    audioElement.id = "audioPlayer";
                                    audioElement.innerHTML = `
                                            <source src="{{ url('') }}/uploads/livechatsounds/${newMessageSound}">
                                        `;

                                    // Play the new audio
                                    if (audioElement.paused) {
                                        audioElement.play();
                                        vibrateTwoTimes();
                                    }

                                    // Set the new audio as the current audio
                                    newMessageSoundCurrentAudio = audioElement;
                                    newMessageSoundCurrentAudio.__proto__.cusumerId = socket.id

                                    // To remove the Sound the chatBody click
                                    if (notificationType == "Loop") {
                                        const clickEventHandler = () => {
                                            newMessageSoundCurrentAudio.pause();
                                            clearInterval(intervalId);
                                            document.querySelector(
                                                    `#operator-conversation-Info[data-id="${socket.id}"]`
                                                ).closest('.main-chat-area')
                                                .removeEventListener('click', clickEventHandler);
                                            intervalId = null
                                        };
                                        if (document.querySelector(
                                                `#operator-conversation-Info[data-id="${socket.id}"]`
                                            )) {
                                            document.querySelector(
                                                `#operator-conversation-Info[data-id="${socket.id}"]`
                                            ).closest('.main-chat-area').addEventListener(
                                                'click', clickEventHandler);
                                        }

                                        function checkAndPlaySound() {
                                            if (audioElement.paused) {
                                                audioElement.play();
                                                vibrateTwoTimes();
                                            }
                                        }

                                        if (!intervalId) {
                                            intervalId = setInterval(checkAndPlaySound, 1000);
                                        }
                                    }
                                }
                            } catch (error) {
                                console.log("engage conversation not getting array");
                            }
                        }
                    })
                }
            })


            let operatorsNotificationsSounds = "{{ setting('operatorsNotificationsSounds') }}"
            let operatorsAgentToAgentWebNot = "{{ setting('operatorsAgentToAgentWebNot') }}"
            let operatorsAgentToAgentSound = "{{ setting('operatorsAgentToAgentSound') }}"
            let operatorsGroupChatWebNot = "{{ setting('operatorsGroupChatWebNot') }}"
            let operatorsGroupChatSound = "{{ setting('operatorsGroupChatSound') }}"


            // For the Operators Notifications
            onlineUsersFindChannel.listen('AgentMessagingEvent', (socket) => {

                // For the Agent To Agent Chat
                if (autID_G == socket.receiverId && !socket.groupInclude && socket.message &&
                    operatorsAgentToAgentSound && parseInt(operatorsAgentToAgentWebNot) && parseInt(
                        operatorsNotificationsSounds)) {
                    let allUsers = '{!! addslashes(json_encode(\App\Models\User::get())) !!}'
                    let SenderImage = JSON.parse(allUsers).filter((arr) => parseInt(arr.id) == parseInt(
                        socket.senderId))[0].image

                    // For the Window notification
                    if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }

                    function notify() {
                        vibrateTwoTimes();
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.senderName, {
                                body: socket.message,
                                icon: SenderImage ?
                                    `${SITEURL}/uploads/profile/${SenderImage}` :
                                    `${SITEURL}/uploads/profile/user-profile.png`,
                                vibration: 5000,
                                data: {
                                    link: `${SITEURL}/admin/operators`
                                }
                            });
                        })
                    }

                    // Stop the current audio if it exists
                    if (newMessageSoundCurrentAudio) {
                        newMessageSoundCurrentAudio.pause();
                        newMessageSoundCurrentAudio.currentTime = 0;
                    }

                    // Create a new audio element
                    let audioElement = document.createElement('audio');
                    audioElement.id = "audioPlayer";
                    audioElement.innerHTML = `
                        <source src="{{ url('') }}/uploads/livechatsounds/${operatorsAgentToAgentSound}">
                    `;

                    // Play the new audio
                    if (audioElement.paused) {
                        audioElement.play();
                        vibrateTwoTimes();
                    }
                    newMessageSoundCurrentAudio = audioElement;

                }


                // For the Grop messages
                if (socket.groupInclude && JSON.parse(socket.groupInclude).includes(parseInt(autID_G)) &&
                    socket.message && operatorsGroupChatSound && parseInt(operatorsGroupChatWebNot) &&
                    parseInt(operatorsNotificationsSounds) && socket.senderId != parseInt(autID_G)) {

                    // For the Window notification
                    if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }

                    function notify() {
                        vibrateTwoTimes();
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.senderName, {
                                body: socket.message,
                                icon: `${SITEURL}/uploads/profile/group.png`,
                                vibration: 5000,
                                data: {
                                    link: `${SITEURL}/admin/operators`
                                }
                            });
                        })
                    }

                    // Stop the current audio if it exists
                    if (newMessageSoundCurrentAudio) {
                        newMessageSoundCurrentAudio.pause();
                        newMessageSoundCurrentAudio.currentTime = 0;
                    }

                    // Create a new audio element
                    let audioElement = document.createElement('audio');
                    audioElement.id = "audioPlayer";
                    audioElement.innerHTML = `
                        <source src="{{ url('') }}/uploads/livechatsounds/${operatorsGroupChatSound}">
                    `;

                    // Play the new audio
                    if (audioElement.paused) {
                        audioElement.play();
                        vibrateTwoTimes();
                    }
                    newMessageSoundCurrentAudio = audioElement;
                }
            })

            notifyalerts();
            notificationsreading();
            badgecount();
            markasreadcount();

            function notifyalerts() {
                $.ajax({
                    url: "{{ route('update.notificationalerts') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $.map(res, function(value, index) {
                            if (index === 0) {
                                toastr.success('{{ lang('You have') }} ' + res.length + ' {{ lang('new notification') }}');
                                readnotify();
                                playAudio();
                            }

                        });
                    }
                });
            }

            let playAudio = () => {
                vibrateTwoTimes();
                let audio = new Audio();
                audio.src = "{{ asset('build/assets/sounds/norifysound.mp3') }}";
                audio.load();
                audio.play();
            }

            function readnotify() {

                $.ajax({
                    url: "{{ route('update.notificationalertsread') }}",
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {

                    }
                });
            }

            function notificationsreading() {
                $('#notifyreading').load('{{ route('notificationsreading') }}')
            }

            function badgecount() {

                $('#badgecount').load('{{ route('badgecount') }}')

            }

            function markasreadcount() {

                $('#markasreadcount').load('{{ route('markasreadcount') }}')

            }
            // For the Live Chat Notification
            Echo.channel('livenotifications').listen('LiveNotification', (socket) => {

                if(socket.userdata.id == autID_G){
                    notifyalerts();
                    notificationsreading();
                    badgecount();
                    markasreadcount();

                    function notify() {
                        vibrateTwoTimes();
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.userdata.name, {
                                body: socket.message,
                                icon: socket.userdata.image != null ? `${SITEURL}/uploads/profile/${socket.userdata.image}` : `${SITEURL}/uploads/profile/user-profile.png`,
                                vibration: 5000,
                                data:{
                                    link : socket.routelink,
                                }
                            });
                        })
                    }

                     // For the Window notification
                     if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }
                }
            })
    </script>
@endif
<script type="text/javascript">

    $(function() {
        "use strict";

        @php echo customcssjs('CUSTOMJS') @endphp

        // Profile Rating
        $(".allprofilerating").starRating({
            readOnly: true,
            starSize: 20,
            emptyColor: '#17263a',
            activeColor: '#F2B827',
            strokeColor: '#556a86',
            strokeWidth: 15,
            useGradient: false
        });

        @if (auth()->user())

            //  Mark As Read
            function sendMarkRequest(id = null) {
                return $.ajax("{{ route('admin.markNotification') }}", {
                    method: 'GET',
                    data: {
                        // _token,
                        id
                    }
                });
            }
            (function($) {

                $('.mark-as-read').on('click', function() {
                    let request = sendMarkRequest($(this).data('id'));
                    request.done(() => {
                        $(this).parents('div.alert').remove();
                    });
                });
                $('.smark-all').on('click', function() {

                    let request = sendMarkRequest();
                    request.done(() => {
                        $('div.alert').remove();
                    })
                });

                $('body').on('click', '.mark-read', function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: '{{ route('admin.notify.markallread') }}',
                        success: function(data) {
                            location.reload();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });

                // Clear Cache
                $('body').on('click', '.sprukoclearcache', function(e) {
                    e.preventDefault();


                    $('.sprukoclearcache').html(
                        '<svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M11 11h2V4q0-.425-.287-.713Q12.425 3 12 3t-.712.287Q11 3.575 11 4Zm-6 4h14v-2H5Zm-1.45 6H6v-2q0-.425.287-.712Q6.575 18 7 18t.713.288Q8 18.575 8 19v2h3v-2q0-.425.288-.712Q11.575 18 12 18t.713.288Q13 18.575 13 19v2h3v-2q0-.425.288-.712Q16.575 18 17 18t.712.288Q18 18.575 18 19v2h2.45l-1-4H4.55l-1 4Zm16.9 2H3.55q-.975 0-1.575-.775t-.35-1.725L3 15v-2q0-.825.587-1.413Q4.175 11 5 11h4V4q0-1.25.875-2.125T12 1q1.25 0 2.125.875T15 4v7h4q.825 0 1.413.587Q21 12.175 21 13v2l1.375 5.5q.325.95-.287 1.725-.613.775-1.638.775ZM19 13H5h14Zm-6-2h-2 2Z"/></svg> Clearing Cache ... <i class="fa fa-spinner fa-spin"></i>'
                    );
                    $.ajax({
                        type: "POST",
                        url: '{{ route('admin.clearcache') }}',
                        success: function(data) {

                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });


            })(jQuery);
        @endif

        // Csrf Field
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        @if(setting('admin_users_inactive_auto_logout') == 'on')
            let lastActve = {!! json_encode(session('last_activity')) !!};
            let showAlertOnceAfter = {!! json_encode(session('showAlertOnceAfter')) !!};
            let logoutOnceafter = {!! json_encode(session('logoutOnceafter')) !!};
            let isPopupShown = false;

            if (localStorage.getItem('showAlertInactive')) {
                $('#adminautologout').modal('show');
            }

            localStorage.setItem('showAlertOnceAfter', showAlertOnceAfter);
            localStorage.setItem('logoutOnceafter', logoutOnceafter);

            setInterval(() => {
                if (new Date(localStorage.getItem('showAlertOnceAfter') ?? showAlertOnceAfter) <=
                    new Date() && !isPopupShown) {
                    localStorage.setItem('showAlertInactive', true);
                    $('#adminautologout').modal('show');
                    isPopupShown = true;
                }
                if (isPopupShown && localStorage.getItem('showAlertInactive')) {
                    if ($('#adminautologout').css('display') === 'none') {
                        $('#adminautologout').modal('show');
                    }
                    if(calculateDateDifferenceInSeconds(localStorage.getItem('logoutOnceafter')) > 0){
                        $('.countdown').html(`${calculateDateDifferenceInSeconds(localStorage.getItem('logoutOnceafter'))}`);
                    }
                }
                if (!localStorage.getItem('showAlertInactive')) {
                    $('#adminautologout').modal('hide');
                }
                if (new Date(localStorage.getItem('logoutOnceafter') ?? logoutOnceafter) <= new Date()) {
                    localStorage.setItem('adminPanelSessionTimeout', true);
                    localStorage.removeItem('showAlertInactive');
                    isPopupShown = false;
                    LogouAdminUSer();
                }
            }, 1000);
            function calculateDateDifferenceInSeconds(dateString) {
                var inputDate = new Date(dateString);
                var currentDate = new Date();
                var differenceInMilliseconds = inputDate - currentDate;
                var differenceInSeconds = Math.floor(differenceInMilliseconds / 1000);
                return differenceInSeconds;
            }
            function LogouAdminUSer() {
                $.ajax({
                    url: "{{ route('admin.sessionLogout') }}",
                    type: 'Post',
                    dataType: 'json',
                    success: function(res) {
                        location.reload();
                    },
                    error: function(res) {
                        if(res?.responseJSON?.error == "Unauthenticated."){
                            setTimeout(() => {
                                window.location.replace("{{ url('admin/login') }}");
                            }, 3000);
                        }
                    }
                });
            }

            $('.adminstayin').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.sessionLogout') }}",
                    type: 'Post',
                    dataType: 'json',
                    data: {
                        stayin: true,
                    },
                    success: function(res) {
                        if (res == 1) {
                            $('.countdown').hide();
                            $('#adminautologout').modal('hide');
                            localStorage.removeItem('showAlertInactive');
                            location.reload();
                        }
                    }
                });
            });
            window.addEventListener('beforeunload', () => {
                localStorage.removeItem('adminPanelSessionTimeout');
                localStorage.removeItem('showAlertInactive');
            })
        @endif

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register("{{ url('/') }}/sw.js")
        }

        var inspectDisable = "{{ setting('inspectDisable') }}"
        var selectDisabled = "{{ setting('selectDisabled') }}"

        if (inspectDisable == 'on') {
            document.addEventListener('contextmenu', function(event) {
                event.preventDefault();
            });
            document.onkeydown = function(e) {
                if (e.code == 'F12') {
                    return false;
                }
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'C')) {
                    e.preventDefault();
                    return false;
                }
            }
        }

        if (selectDisabled == 'on') {
            document.addEventListener('selectstart', function(event) {
                const editableElements = ['INPUT', 'TEXTAREA', 'DIV'];
                const isEditable = editableElements.some(tag => event.target.tagName === tag && event.target.isContentEditable);

                if (!isEditable) {
                    event.preventDefault();
                }
            });
        }

        $('#ticketcreateforcust').on('shown.bs.modal', function () {
            $('.select2create').select2({
                dropdownParent: $('#ticketcreateforcust')
            });
        });

        // Select2 by showing the search
        $('.select2create-show-search').select2({
            minimumResultsForSearch: ''
        });

        let elementToToggle = null;
        // admin ticket create for customer
        $('#adminticketcreateforcust').on('click', function(event) {
            elementToToggle = event.target;
            $('#ticketcreateforcust').modal('show');
        });

        $('#ticketcreateforcust #create_ticket_form_btn').on('click', function() {
            elementToToggle.checked = !elementToToggle.checked;
        })

        $('#creattcategory').on('change',function(e) {
            e.preventDefault();
            var cat_id = e.target.value;
            $('#selectssSubCategorys').hide();
            $.ajax({
                url:"{{ route('guest.subcategorylist') }}",
                type:"POST",
                    data: {
                    cat_id: cat_id
                    },
                    cache : false,
                    async: true,
                success:function (data) {
                    if(data.subcategoriess){
                        $('#selectssSubCategorys').hide()
                        $('#subscategorys').html(data.subcategoriess)
                        $('#selectssSubCategorys').show()
                    }
                    else{
                        $('#selectssSubCategorys').hide();
                        $('#subscategorys').html('')
                    }

                    if(data.subCatStatus.length === 0){
                        $('#selectssSubCategorys').hide();
                    }

                    if(data.categorypriority){
                        $('#priorityy').val(data.categorypriority).trigger('change');
                    }else{
                        $('#priorityy').val('').trigger('change');
                    }
                    @if(setting('ENVATO_ON') == 'on')
                        if(data.envatosuccess.length >= 1){
                            $('#envato_id')?.empty();
                            $('#envatopurchases .row')?.remove();
                            let selectDiv = document.querySelector('#envatopurchases');
                            let Divrow = document.createElement('div');
                            Divrow.setAttribute('class','row mt-4');
                            let Divcol3 = document.createElement('div');
                            Divcol3.setAttribute('class','col-md-12');
                            let selectlabel =  document.createElement('label');
                            selectlabel.setAttribute('class','form-label mb-0 mt-2')
                            selectlabel.innerHTML = "Envato Purchase Code <span class='text-red'>*</span>";
                            let divcol9 = document.createElement('div');
                            divcol9.setAttribute('class', 'col-md-12');
                            let selecthSelectTag =  document.createElement('input');
                            selecthSelectTag.setAttribute('class','form-control');
                            selecthSelectTag.setAttribute('type','search');
                            selecthSelectTag.setAttribute('id', 'envato_id');
                            selecthSelectTag.setAttribute('name', 'envato_id');
                            selecthSelectTag.setAttribute('placeholder', 'Enter Your Purchase Code');
                            let selecthSelectInput =  document.createElement('input');
                            selecthSelectInput.setAttribute('type','hidden');
                            selecthSelectInput.setAttribute('id', 'envato_support');
                            selecthSelectInput.setAttribute('name', 'envato_support');
                            let envatoError = document.createElement('span');
                            envatoError.setAttribute('id', 'envatoError');
                            envatoError.setAttribute('class', 'text-danger alert-message');
                            selectDiv.append(Divrow);
                            Divrow.append(Divcol3);
                            Divcol3.append(selectlabel);
                            divcol9.append(selecthSelectTag);
                            divcol9.append(selecthSelectInput);
                            Divrow.append(divcol9);
                            Divrow.append(envatoError);
                            $('.purchasecode').attr('disabled', true);

                            document.querySelector('#envato_id').onkeyup = (e) => {
                                let value = e.target.value;
                                if (value != '') {
                                    if(value.length == '36'){
                                        var _token = $('input[name="_token"]').val();
                                        $.ajax({
                                            url: "{{ route('guest.envatoverify') }}",
                                            method: "POST",
                                            data: {data: value, _token: _token},

                                            dataType:"json",

                                            success: function (data) {
                                                if(data?.valid == 'true'){
                                                    $('#envato_id').addClass('is-valid');
                                                    $('#envato_id').attr('readonly', true);
                                                    $('.purchasecode').removeAttr('disabled');
                                                    $('#productnamee').val(data.name);
                                                    $('#productnamee').attr('readonly', true);
                                                    $('#itemnamee').val(data.name);
                                                    $('#hideelementt').removeClass('d-none');
                                                    $('#envato_id').css('border', '1px solid #02f577');
                                                    $('#envato_support').val('Supported');
                                                    $('#expired_note').addClass('d-none');
                                                    licensekey = data.key
                                                    toastr.success(data.message);
                                                    $('#create_ticket_form_btn').prop('disabled', false)
                                                }
                                                if(data?.valid == 'expried'){
                                                    @if(setting('ENVATO_EXPIRED_BLOCK') == 'on')

                                                        $('.purchasecode').attr('disabled', true);
                                                        $('#envato_id').css('border', '1px solid #e13a3a');
                                                        $('#productnamee').val(data.name);
                                                        $('#productnamee').attr('readonly', true);
                                                        $('#itemnamee').val(data.name);
                                                        $('#hideelementt').removeClass('d-none');
                                                        $('#envato_support').val('Expired');
                                                        $('#expired_note').removeClass('d-none');
                                                        toastr.error(data.message);
                                                    @endif
                                                    @if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
                                                        $('#envato_id').addClass('is-valid');
                                                        $('#envato_id').attr('readonly', true);
                                                        $('.purchasecode').removeAttr('disabled');
                                                        $('#productnamee').val(data.name);
                                                        $('#productnamee').attr('readonly', true);
                                                        $('#itemnamee').val(data.name);
                                                        $('#hideelementt').removeClass('d-none');
                                                        $('#envato_id').css('border', '1px solid #02f577');
                                                        $('#expired_note123').removeClass('d-none');
                                                        $('#envato_support').val('Expired');
                                                        licensekey = data.key
                                                        toastr.warning(data.message);
                                                    @endif

                                                }
                                                if(data?.valid == 'false'){
                                                    $('.purchasecode').attr('disabled', true);
                                                    $('#envato_id').css('border', '1px solid #e13a3a');
                                                    toastr.error(data.message);
                                                }


                                            },
                                            error: function (data) {

                                            }
                                        });
                                    }
                                }else{
                                    toastr.error('Purchase Code field is Required');
                                    $('.purchasecode').attr('disabled', true);
                                    $('#envato_id').css('border', '1px solid #e13a3a');
                                }
                            }

                        }else{
                            $('#hideelementt').addClass('d-none');
                            $('#envato_id').val('');
                            $('#envato_id')?.empty();
                            $('#envatopurchases .row')?.remove();
                            $('.purchasecode').removeAttr('disabled');
                        }
                    @endif
                },
                error:(data)=>{

                }
            });
        });

        var licensekey;

        // Error message is showing after reloading the page
        let errorMessageShow = localStorage.getItem('errorMessage');
        if(errorMessageShow){
            toastr.error(errorMessageShow);
            localStorage.removeItem('errorMessage');
        }

        $('#subjects').on('keyup', function(e){
            if(e.target.value.length == {{setting('TICKET_CHARACTER')}}){
                $('#subjectmaxtexts').removeClass('text-muted')
                $('#subjectmaxtexts').addClass('text-red');
            }else{
                $('#subjectmaxtexts').removeClass('text-red')
                $('#subjectmaxtexts').addClass('text-muted');
            }
            localStorage.setItem('adminsubject', e.target.value)
        })

        $('body').on('submit', '#create_ticket_form', function(e) {
            e.preventDefault();

            $('#create_ticket_form_btn').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
            $('#create_ticket_form_btn').prop('disabled', true);
            var formData = new FormData(this);
            formData.set('envato_id', licensekey);

            $.ajax({
                type: 'POST',
                url: '{{route('livechat.ticketcreate')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                success: (data) => {
                    if(data?.success){
                        toastr.success(data.success);
                        location.reload();
                    }
                },
                error: function(data) {
                    $('#create_ticket_form_btn').prop('disabled', false);
                    $('#create_ticket_form_btn').html('Save');
                    if(data.responseJSON.message == 'The payload is invalid.'){
                        toastr.error('You are not allow to change this password.');
                    }else{
                        if(data?.responseJSON?.errors){
                            $('#subjectError').html(data.responseJSON.errors.subject);
                            $('#emailError').html(data.responseJSON.errors.email);
                            $('#CategorysError').html(data.responseJSON.errors.category);
                            $('#assigneeError').html(data.responseJSON.errors.assigneeid);
                            $('#messageError').html(data.responseJSON.errors.message);
                        }
                        if(data?.responseJSON?.message == 'domainblock'){
                            $('#emailError').html(data.responseJSON.error);
                        }
                        if(data?.responseJSON?.message == 'envatoerror'){
                            $('#envatoError').html(data.responseJSON.error);
                        }
                        if(data?.responseJSON?.message == 'subcaterror'){
                            $('#subcategoryError').html(data.responseJSON.error);
                        }
                    }
                }
            });

        });

    })
</script>
