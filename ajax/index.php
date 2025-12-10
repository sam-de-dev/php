<!DOCTYPE html>
<html>
<head>
    <title>AJAX Messages</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <button>Get next message</button>
    <div id="message"></div>

    <script>
        var last_id = 0;

        function get_message(){
            $.getJSON('get_next_message.php?id=' + last_id, function(data){
                $('#message').append('<div>'+data.msg+'</div>');
                if (data.msg !== "No more messages.") {
                    last_id = data.id + 1;
                }
            });
        }

        $('button').click(function(){
            get_message();
        });
    </script>
</body>
</html>