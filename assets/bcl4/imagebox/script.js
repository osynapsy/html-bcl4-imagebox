BclImageBox =
{
    init : function ()
    {
        $(window).resize(function(){
            setTimeout(
                function(){
                    $('img.imagebox-main').each(function(){
                        BclImageBox.initCropBox(this);
                    });
                },
                1000
            );
        });
        $('.osy-imagebox-bcl').on('change','input[type=file]',function(){
            BclImageBox.upload(this);
        }).on('click','.crop-command', function(){
            BclImageBox.crop(this);
        }).on('click','.zoomin-command, .zoomout-command', function(){
            BclImageBox.zoom(this);
        });
        $(window).resize();
    },
    initCropBox : function(img)
    {
        const cropBoxWidth = img.closest('.crop').dataset.maxWidth;
        const cropBoxHeight = img.closest('.crop').dataset.maxHeight;        
        const preserveAspect = img.closest('.crop').dataset.preserveAspectRatio ? true : false;
        $(img).rcrop({
            minSize : [cropBoxWidth, cropBoxHeight],
            //maxSize : [cropBoxWidth, cropBoxHeight],
            preserveAspectRatio : true,
            grid : true
        });
    },
    zoom : function(button)
    {
        const parent = button.closest('.osy-imagebox-bcl');
        const factor = button.classList.contains('zoomout-command') ? -0.05 : 0.05;
        let data = $('img.imagebox-main', parent).rcrop('getValues');
        let params = [
            data.width * (1 + factor),
            data.height * (1 + factor),
            data.x,
            data.y
        ];
        $('img.imagebox-main', parent).rcrop('resize', params[0], params[1], params[2], params[3]);
    },
    crop : function(button)
    {
        const wrapper = button.closest('.osy-imagebox-bcl');
        const image = wrapper.querySelector('img.imagebox-main');
        const fieldId = wrapper.querySelector('input[type=hidden]').getAttribute('id');       
        const cropObj = $(image).rcrop('getValues');        
        const cropData = [cropObj.width, cropObj.height, cropObj.x, cropObj.y].valueOf();
        const newDim = [wrapper.dataset.maxWidth, wrapper.dataset.maxHeight].valueOf();
        let jsOnSuccess = "(function() {";
            jsOnSuccess += "document.getElementById('" + fieldId + "').value = '%s';";
            jsOnSuccess += "Osynapsy.refreshComponents(['"+ wrapper.id +"'], function() { BclImageBox.init() });";
            jsOnSuccess += "})();";
        image.dataset.actionParameters = Array.from([image.src, btoa(cropData), btoa(newDim), btoa(jsOnSuccess)]).join(',');
        Osynapsy.action.execute(image);
    },
    upload : function (input)
    {
        const filepath = input.value;
        const m = filepath.match(/([^\/\\]+)$/);
        const filename = m[1];
        $('.osy-imagebox-filename').text(filename);
        Osynapsy.action.execute(input.closest('.osy-imagebox-bcl'));
    }
};

if (window.Osynapsy) {
    Osynapsy.plugin.register('BclImageBox',function() {
        BclImageBox.init();
    });
}