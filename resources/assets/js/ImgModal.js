function ShowImg(src){
    let target = $("#image-dialog");
    let imgTarget = target.find('img');
    imgTarget.attr('src', src);

    return target.modal('show');
}

$(function(){
   $('img.pop-up').click(function(){
       ShowImg(this.src);
   });
});

module.exports = {
    ShowImg: ShowImg,
};