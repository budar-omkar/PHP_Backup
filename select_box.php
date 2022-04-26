<div class="select-container">
        <div id="sel-body">
        </div><button id="sel-btn" type="button">+</button>
        <div id="drop-area">
        </div>
    </div>

    <style>
        .select-container{
            height: 30px;
            width: 300px;
            background-color: black;
            display: flex;
            border: 1px solid black;
            position: static;
            overflow: visible;
        }
        
        #sel-body{
            height: 99%;
            width: 80%;
            background-color: white;
            overflow-x: auto;
        overflow-y: hidden;
            
        }

        #sel-btn{
            height: 99%;
            width: 20%;
            background-color: red;
            color: white;
        }
        #drop-area{
            height: auto;
            width: 300px;
            background-color: pink;
            position: absolute;
            margin-top: 29px;
            padding-top: 1px;
            display: none;
        }

        .val{
            
            width: 100%;
            height: fit-content;
            background-color: white;
            border: 1px solid black;
        }

        .sel-val
        {
            width: fit-content;
            border: 1px solid black;
            margin: 5px 5px;
            display: inline-block;
        }
        body{
            height: 200vh;
        }
    </style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // const arr = {name:'omkar' , lastname:'budar', age:21, gender:'male',val1:'val1', val2:'val2', val3:'val3'}
    const arr = {}
<?
// $sql = "SELECT * FROM vendors ";
$res = exec_qry($sql);
$result = $res['result'];
while ($row = mysqli_fetch_array($result)) {
  extract($row);
?>
    Object.assign(arr, {<?=$id?>: "<?=$val?>"});

<?

}
?>
    $(document).ready(function(){

        for (const property in arr) 
        {
            var div = document.createElement('button')
            div.className = 'val'
            div.id = `${property}`+'-btn'
            div.innerHTML = `${arr[property]}`
            div.value =  `${property}`
            div.dataset.val =`${arr[property]}`
            div.type = 'button'
            $("#drop-area").append(div)
        }

    $("#sel-btn").click( function(){
        // alert()
        if($('#drop-area').css('display') == 'block')
        {
            $('#drop-area').css('display', 'none')
        }
        else{
            $('#drop-area').css('display', 'block')
        }
    })
    
    $('.val').click(function(){
        $('#drop-area').css('display', 'none')
        var sel_div_id = $(this).attr('id')
        var sel_div_val = $(this).val()
        var sel_div_data_val = $(this).attr('data-val')


        var sel_val = document.createElement('div')
        sel_val.className = 'sel-val'
        sel_val.id = sel_div_id +'-div' 
        sel_val.innerHTML = sel_div_data_val

        var Xbtn = document.createElement('button')
        Xbtn.innerHTML = 'x'
        Xbtn.className = 'close-btn'
        Xbtn.id = sel_div_id
        Xbtn.value = sel_div_val
        Xbtn.dataset.val = sel_div_data_val
        $('#sel-body').append(sel_val)
        $("#"+sel_div_id+'-div').append(Xbtn)
        $(this).hide()
        // alert()
    })

    $(document ).on( "click", ".close-btn", function() {
        var div_id = $(this).attr('id')
        var div_val = $(this).val()
        $('#'+div_id+'-div').remove()
        $('#'+div_val+'-btn').show()
});



    })

</script>