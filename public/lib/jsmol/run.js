//Created by Ben Robinson (ben@codewild.org)
//Requires JQuery

$(function () {
    $('#toolbarHandle').click(function () {
        toggleToolbar();
    });
    $('.swatch input').click(setBGC);
    $('#aaButton').click(toggleAA);
    $('#speedSlider').change(setSpeed)
    $('#labelCheckbox').click(userLabels);
    $('#labelOptions').change(function () {
        $('#labelCheckbox').prop('checked', true);
        setLabels($('#labelOptions').val())
    })
    $('#spinButton').click(toggleSpin);
    $('#selectAll').click(toggleAll);
    $('#selectField input').click(userSelect)
    $('#selectField select').change(function () {
        $(this).prev().prop('checked', true);
        userSelect();
    });
    $('#displayField input').click(userDisplay);
    $('#displayField select').change(function () {
        $(this).prev().prop('checked', true);
        userDisplay();
    });
    $('#colorField input').click(userColor)
    $('#colorField select').change(function () {
        $(this).prev().prop('checked', true);
        userColor();
    });
    $('#customText').on('keydown', function (e) {
        let oldError = Jmol.evaluateVar(jsmolApplet, "_errorMessage");
        userScript = $('#customText').val();
        //Enter
        if (e.which == 13 && userScript != '') {
            //Write script
            jsmol(userScript);
            let error = Jmol.evaluateVar(jsmolApplet, "_errorMessage");
            if (error != null && error != oldError) {
                //Best way so far to only report the error message if it is new. Does not report scripting messages, only script compiler errors
                alert(error);
            }
            //Save script to history
            consoleHistory.push(userScript);
            //Advance iterator
            h = consoleHistory.length;
            //Clear input
            $(this).val('');
        }
        //Up arrow
        else if (e.which === 38 && h>0) {
            h--;
            $(this).val(consoleHistory[h]);
        }
        //Down arrow
        else if (e.which === 40 && h<consoleHistory.length) {
            h++;
            $(this).val(consoleHistory[h]);
        }
    });
    $('#resetButton').click(function () {
        forceLabels(false);
        forceSpin(false);
        resetSelectElems();
        jsmol(reset + defaultSpin + subsetAll + resetAll + ballAndStick);
    });
    $('#clearButton').click(function () {
        forceLabels(false);
        forceSpin(false);
        resetSelectElems();
        halosLogic();
    });
    $('#writeButton').click(function () {
        jsmol('write image 1000 1000 PNGJ 10 "fileName.png";');
    })
    $('#defaultButton').click(function () {
        defaultSettings();
    });

    //#Viewer is not specific enough. Would also like to find a way to work in spin
    $('#viewer').click(function () {
        $('.camera').removeClass('toggled');  
    });
});

//Page Unload
// $(window).unload(function () {
//     sessionStorage.setItem("sessionBGC", $('.swatch input:checked').val());
//     sessionStorage.setItem("sessionAA", $('#aaButton').hasClass('toggled'));
//     sessionStorage.setItem("sessionSpeed", $('#speedSlider').val());
//     sessionStorage.setItem("sessionToolbar", $('#toolbar').hasClass('showToolbar'));
// });