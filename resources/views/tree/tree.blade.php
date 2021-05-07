<!DOCTYPE html>
<html lang="en">
<head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Tree</title>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
          <link rel="stylesheet" href="{{ asset('css/style.css') }}">







</head>
<body>

          <form method='post' id='form' >
              @csrf


          <input type="text" name='newCategoryName' id='newCategoryName' readonly="readonly">
          <input type="text" name='idParent'  id='idParent'>



          <input type="text" name='move' id='move' readonly="readonly">
          <input type="text" name='moveTo'  id='moveTo' readonly="readonly"><br><br>


          <input type="text" name='deleteElementName' id="deleteElementName" readonly="readonly">
          <input type="text" name='deleteElement' id="deleteElement">


          Show in order: <select name="order" id="order">
                 <option value="asc" >A-Z</option>
                 <option value="desc">Z-A</option>
                 <option value="">my order</option>

                 <input type="submit" name='sort' value='sort' id='sort'><br><br>

          </select>



          {!! $htmlStructure !!}

          </form>

          <script src = "{{ asset('js/script.js') }}"></script>

</body>
