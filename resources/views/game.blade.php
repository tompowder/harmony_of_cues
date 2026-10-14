<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
  </head>
<body class="body" id="body">

   <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="sumit">logout</button>
   </form><br>

   <input type="number" id="x" value="{{ $coordinates["x"] }}"><br>
   <input type="number" id="y" value="{{ $coordinates["y"] }}"><br>

   <div style="font-family:Consolas, monospace" id="display">
   @forelse($display as $displayPart)
   {!! $displayPart !!} <br>
   @empty 
      there are no map
   @endforelse

   {!! $script !!}
   </div>
    
</body>

<script>
  const display = document.querySelector("#display");
  const body = document.querySelector("#body");

  const elementX = document.querySelector("#x");
  const elementY = document.querySelector("#y");

  let moving = false;

  async function sendAction(direction = null)
  {
    if (moving) 
    {return;}

    moving = true;

    try 
    {
      const response = await fetch("/player-action", 
      {
        method: "POST",
        headers: 
        {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(
        {
            direction
        })
      });
      let update = await response.json();

      //console.log(update);

      if(update.errors)
      {throw new Error(update.message);}

      elementX.value = update.coordinates.x;
      elementY.value = update.coordinates.y;

      display.innerHTML = "";

      let i = 0;
      while (update.display[i]) 
      {
        display.innerHTML += "<span id='"+i+"'>" + update.display[i++] + "</span><br>"
      }
    } 
    catch (e) 
    {
      console.error(e);
      //location.reload(); 
    }
    finally
    {moving = false;}
  }
  
  body.addEventListener("keydown", (event) => {
    const directions = {
        ArrowLeft: "left",
        ArrowRight: "right",
        ArrowUp: "up",
        ArrowDown: "down",
        Enter: "enter",
        KeyS: "s"
    };

    const direction = directions[event.code];

    if (direction) {
        event.preventDefault();
        sendAction(direction);
    }
  });

</script>

</html>