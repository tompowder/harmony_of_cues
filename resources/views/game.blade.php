<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
  </head>
  <body class="body" id="body">

    <form method="POST" action="/ideas" id="test">
      @csrf
      <input type="number" id="x" name="x" value="{{ $coordinates["x"] }}"><br>
      <input type="number" id="y" name="y" value="{{ $coordinates["y"] }}"><br>
      <button type="submit"> submit </button>
    </form>

    <div style="font-family:Consolas, monospace" id="map">
      @forelse($map as $mapPart)
      {!! $mapPart !!} <br>
      @empty 
        there are no map
      @endforelse

      {!! $script !!}
    </div>
    
  </body>

  <script>
  const form = document.querySelector("#test");
  const map = document.querySelector("#map");
  const body = document.querySelector("#body");

  const elementX = document.querySelector("#x");
  const elementY = document.querySelector("#y");

  let moving = false;

  async function sendData()
  {
    if (moving) 
    {return;}

    moving = true;

    const formData = new FormData(form);
    
    try 
    {
      const response = await fetch("/player-move", 
      {
        method: "POST",
        body: formData,
      });
      let update = await response.json();

      //console.log(update);
      
      elementX.value = update.coordinates.x;
      elementY.value = update.coordinates.y;

      map.innerHTML = "";

      let i = 0;
      while (update.map[i]) 
      {
        map.innerHTML += "<span id='"+i+"'>" + update.map[i++] + "</span><br>"
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

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    sendData(x.value, y.value);
  });
  
  body.addEventListener("keydown", (event) => {
    let x = Number(elementX.value);
    let y = Number(elementY.value);

    if(event.code === "ArrowLeft")
    {x--;}

    if(event.code === "ArrowRight")
    {x++;}

    if(event.code === "ArrowUp")
    {y--;}

    if(event.code === "ArrowDown")
    {y++;}

    elementX.value = x;
    elementY.value = y;
    sendData();
  });

  form.reset();

  </script>

</html>