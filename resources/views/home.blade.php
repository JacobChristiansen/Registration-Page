<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META -->
    <title>{{ env('AZ_NAME') }}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon.ico" />
    
    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/tailwind.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>
<body>
    <img src="./assets/img/bg.webp" class="bg_img">
    <div class="grid h-screen place-items-center">
    <div class="flex justify-center">
        <div class="rounded-lg shadow-lg bg-white max-w-sm dark:bg-slate-800" style="width:24rem;">
          <div class="p-6">
            <h5 class="text-gray-900 text-xl font-medium mb-2 dark:text-white">{{ env('AZ_HEADLINE') }}</h5>
            <form method="post" action="../register">
                @csrf
                <div class="mb-6">
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Username</label>
                    <input type="text" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="AzureDev" name="username" required>
                </div>

                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
                    <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@ac-web.org" name="email" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Password</label>
                    <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="password" required>
                </div>

                <div class="mb-6">
                    <label for="cpassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Confirm Password</label>
                    <input type="password" id="cpassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="cpassword" required>
                </div>
                    
                <button id="regbut" type="submit" class="inline-block px-6 py-2.5 bg-gray-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0  active:shadow-lg transition duration-150 ease-in-out w-full" disabled>Register</button>
            </form>
            @if (session()->has('success'))
                <span class="text-green-400">{{ session('success') }}</span>
            @endif
            @if (session()->has('status'))
                <span class="text-red-400">{{ session('status') }}</span> 
            @endif
            <!--
                Error with registration, potentially add error code or give reason.
                <span class="text-red-400">Error with registration, try again!</span> 
            -->
            <!-- 
                Error with server communication, SQL conn err / no conn established
                <span class="text-red-400">Error communicating with server, try again later.</span> 
            -->

                <!-- Use text-red-400 if server offline -->
                <!-- Use "fa-solid fa-bolt-slash" if server offline-->
                @if($status)
                <div class="text-center dark:text-white"><i class="fa-solid fa-bolt text-green-400"></i> Server Status <i class="fa-solid fa-bolt text-green-400"></i></div>
                @else
                <div class="text-center dark:text-white"><i class="fa-solid fa-bolt text-red-400"></i> Server Status <i class="fa-solid fa-bolt text-red-400"></i></div>
                @endif
                <div class="grid grid-cols-4 gap-2 text-center dark:text-gray-400">
                    <div><i class="fa-solid fa-user text-green-400"></i> {{DB::table('account')->count()}}</div> 
                    <div><i class="fa-solid fa-clock text-cyan-400"></i> <span id="uptimeHrs"></span>:<span id="uptimeMin"></span></div>
                    <div><i class="fa-solid fa-shield-virus text-red-400"></i> {{DB::table('account')->count()}}</div>
                    <div><i class="fa-solid fa-users text-orange-400"></i> {{DB::table('account')->count()}}</div>
                </div>
            <code class="text-gray-700 text-base mb-4 dark:text-slate-400 bg-gray-100 dark:bg-gray-800">
                {{ env('AZ_REALMLIST') }}
            </code>
            </div>
          </div>
        </div>
      </div>
      </div>
<script src="./assets/js/jquery-3.6.0.min.js"></script>
<script>
    $( document ).ready(function() {
        // timer
        function engageTimer()
        {
        @if ($uptime)
        var serverStart = {{$uptime->starttime}}; //Get server start time from DB
        @else
        var serverStart = Math.floor(Date.now()/1000);
        @endif
        var currentTimeInSeconds=Math.floor(Date.now()/1000);
        var serverUp = Math.abs(currentTimeInSeconds - serverStart);
        var upHrs = Math.floor(serverUp / 3600);
        var upMin = Math.floor((serverUp % 3600) / 60);
        if(upHrs < 10)
            $("#uptimeHrs").text("0"+upHrs);
        else
            $("#uptimeHrs").text(upHrs);

        if(upMin < 10)
            $("#uptimeMin").text("0"+upMin);
        else
            $("#uptimeMin").text(upMin);
        }
        engageTimer();
        setInterval(engageTimer, 3000)
        // Password confirmation, makes sure the password match!
        $( "#password, #cpassword" ).keyup(function() {
            if($("#password").val() == $("#cpassword").val())
            {
                $("#password").addClass("dark:border-green-600 border-green-600");
                $("#cpassword").addClass("dark:border-green-600 border-green-600");
                $("#regbut").addClass("dark:bg-green-600 bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-700");
                if($("#password").hasClass("border-red-600"))
                {
                    $('#regbut').removeAttr("disabled");
                    $("#password").removeClass("dark:border-red-600 border-red-600");
                    $("#cpassword").removeClass("dark:border-red-600 border-red-600");
                }
            } else
            {
                $("#password").addClass("dark:border-red-600 border-red-600");
                $("#cpassword").addClass("dark:border-red-600 border-red-600");
                

                if($("#password").hasClass("border-green-600"))
                {
                    $("#regbut").removeClass("dark:bg-green-600 bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-700");
                    $("#regbut").attr("disabled", true);
                    $("#password").removeClass("dark:border-green-600 border-green-600");
                    $("#cpassword").removeClass("dark:border-green-600 border-green-600");
                }
            }
        });
        
    });
</script>

</body>
</html>