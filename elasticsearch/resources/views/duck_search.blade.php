 <html>
    <head>
    </head>
 

 <body>
  <h1>search Duck</h1>


  <form action="{{ route('duck_search') }}" method='get'>
  First name:<br>

  <input type="text" name="query" >
  <input type="submit" value="search"> 

  </form>
  <br>
</form> 


<table>
    <tr>
        <th>name</th>
        <th>age</th>
        <th>color</th>
        <th>gender</th>
        <th>Hometown</th>
        <th>Funky?</th>
        <th>About</th>
        <th>registered Date</th>
    </tr>
    <tr>
        @foreach($results as $result)
            <tr>
                <td> {{  $result->name }} </td>
                <td> {{  $result->age }} </td>
                <td> {{  $result->color }} </td>
                <td> {{  $result->gender }} </td>
                <td> {{  $result->hometown }} </td>
                <td> {{  $result->funkyDuck }} </td>
                <td> {{  $result->about }} </td>
                <td> {{  $result->registered }} </td>
            </tr>
        @endforeach    
    </tr>
</table>

 </body>
 </html>