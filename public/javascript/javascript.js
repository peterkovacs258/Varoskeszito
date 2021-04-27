$(document).ready(function(){
    let status=0;
    let currentHtml="";
/*Amennyiben a felhasználó kiválasztott egy megyét megjelenítjük a hozzá tartozó városokat,
És az új város felvétele lehetőséget*/
$(document).on('change','#megyeSelect',function(){
    let val=$(this).val();
    $.ajax({
        url:"http://localhost/varoskeszito/index/varosokAjax",
        type:'post',
        data:{megyeid:val},
        success:function(res){
           $('.rightSide').html(res);
           $("#ujVarosDiv").show();
          
        }
    })
});

//Új város felvétele a kiválasztott megyéhez
$(document).on('click','.btnFelveszVaros',function(){
    let megyeid=$('#megyeSelect').val();
    let varosnev=$('#varosInput').val();
    //Ellenőrizzük hogy nem üres-e a beviteli mezőnk
    if(varosnev.length>1)
    {
        $.ajax({
            url:"http://localhost/varoskeszito/index/ujVarosAjax",
            type:'post',
            data:{megyeid:megyeid,
                varosnev:varosnev},
            success:function(res){
                $('.rightSide').html(res);
            }
        })
    }

    console.log(megyeid,varosnev);
})

//Városra kattintva az szerkeszthetővé válik
$(document).on('click','td',function(){
if(status==0)
{
    status=1;
    let id=$(this).data('id');
    let val=$(this).html();
    currentHtml=val;
    console.log(currentHtml);
    $(this).html("<input type='text' value='"+val+"'>"+
    "<input type='button' data-id="+id+" class='btn btn-danger'  id='delCity' value='Töröl'>"+
    "<input type='button' data-id="+id+" class='btn btn-success' id='modCity' value='Módosít'>"+
    "<input type='button' data-id="+id+" class='btn btn-primary 'id='cancel' value='Mégsem'>");
    console.log(id,val);
}
else if (status==2)
{
    status=0;
}


});

//Mégse gombbal kilépünk a szerkesztésből
$(document).on('click','#cancel',function(){
    let id=$(this).data('id');
    $("td[data-id='"+id+"']").html(currentHtml);
    status=2;

});

//A törlés gombbal töröljük az adott elemet
$(document).on('click','#delCity',function(){
    let id=$(this).data('id');
        $.ajax({
            url:"http://localhost/varoskeszito/index/torolVarosAjax",
            type:'post',
            data:{id:id},
            success:function(res){
                $('.rightSide').html(res);
            }
        })
})


});