$(document).ready(function(){
    /*A statusz változó jelöli egy város elem jelenlegi státuszát, 0 = semleges
    1 aktív, rá lett kattintva, 2 már nem aktív, de még nem is semleges*/
    let status=0;
    //A jelenlegi aktív város elem kattintáskor létező értéke
    let currentHtml="";
    //A jelenlegi aktív város elem adatbázisban hozzácsatolt id-je
    let currentId=0;
/*Amennyiben a felhasználó kiválasztott egy megyét megjelenítjük a hozzá tartozó városokat,
És az új város felvétele lehetőséget*/
$(document).on('change','#megyeSelect',function(){
    let val=$(this).val();
    $('.rightSide').hide();
    $.ajax({
        url:"http://localhost/varoskeszito/index/varosokAjax",
        type:'post',
        data:{megyeid:val},
        success:function(res){
            status=0;
           $('.rightSide').html(res);
           $('.rightSide').show(300);
           $("#ujVarosDiv").show(300);
          
        }
    })
});

//Új város felvétele a kiválasztott megyéhez
$(document).on('click','.addCity',function(){
    let megyeid=$('#megyeSelect').val();
    let varosnev=$('#varosInput').val();
    $("#varosInput").removeClass("shakeit");
    //Ellenőrizzük hogy nem üres-e a beviteli mezőnk
    if(varosnev.length>1)
    {
        $.ajax({
            url:"http://localhost/varoskeszito/index/ujVarosAjax",
            type:'post',
            data:{megyeid:megyeid,
                varosnev:varosnev},
            success:function(res){
                status=0;
                $('.rightSide').html(res);
            },
            statusCode: {
                403: function() {
                    $("#varosInput").addClass("shakeit");
                    alert("Már létezik ez a város az adatbázisban!");
                }
              }
        })
    }
    else
    {
        $("#varosInput").addClass("shakeit");
    }

})

//Városra kattintva az szerkeszthetővé válik
$(document).on('click','td',function(){
    if(status==0)
    {
        //külső változókban eltároljuk az adott td elem városának adatait
        currentId=$(this).data('id');
        currentHtml=$(this).html();
        //Megjelenítjük a három event gombot
        $(this).html("<div class='form-row'>"+
            "<input type='text' class='form-control' id='inputVaros' value='"+currentHtml+"'>"+
         "<i data-id="+currentId+" class='fas fa-trash-alt btn btn-danger' id='delCity' ></i>"+
         "<i data-id="+currentId+" class='fas fa-edit btn-success btn' id='modCity' ></i>"+
        "<i  data-id="+currentId+" class='far fa-times-circle btn btn-primary' id='cancel' ></i>"+
        "</div>");
        status=1;
    }
    else if(status==2)
    {
     status=0;
    }


});

//Mégse gombbal kilépünk a szerkesztésből
$(document).on('click','#cancel',function(){
    $("td[data-id='"+currentId+"']").html(currentHtml);
    status=2;

});

//A törlés gombbal töröljük az adott elemet
$(document).on('click','#delCity',function(){
        $.ajax({
            url:"http://localhost/varoskeszito/index/torolVarosAjax",
            type:'post',
            data:{id:currentId},
            success:function(res){
                status=0;
                $('.rightSide').html(res);
                
            }
        });
});

//Módosítás gombra való kattintásra, amennyiben változás történt módosítjuk az adott várost
$(document).on("click","#modCity",function(){
    let modifiedHtml=$("#inputVaros").val();
    let megyeid=$('#megyeSelect').val();
    //Csak akkor fusson le ha történt változás
    if(currentHtml!=modifiedHtml)
    {
        if(modifiedHtml.length>1)
        {
            $.ajax({
                url:"http://localhost/varoskeszito/index/modositVarosAjax",
                type:'post',
                data:{id:currentId,
                      modCity:modifiedHtml,
                      megyeid:megyeid},
                success:function(res){
                    status=0;
                    $('.rightSide').html(res);
                    
                }
            });
        }
    }
    else
    {
        $('#inputVaros').addClass('shakeit');
    }

});


});