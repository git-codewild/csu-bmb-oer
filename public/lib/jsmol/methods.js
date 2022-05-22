//Definitions
//Created by Ben Robinson (butterybikes@gmail.com). Last update 1/28/2020.

var bgColorList = ["lightblue", "darkblue", "black", "grey", "lightgrey", "white"];
var colorList = {
    cpk: cpk,
    shapely: 'if (@userSelection == "") {userSelection = {displayed};};select @userSelection and protein;color amino;select @userSelection and nucleic;color shapely;',
    structure: colorStructure,
    charge: 'calculate formalCharge;color formalCharge;',
    relativeTemperature: 'color relativeTemperature;',
    atomno: 'color property atomno;',
    resno: 'color property resno;',
    chainNo: 'color property chainNo;',
    pink: 'color pink;',
    red: red,
    orange: 'color orange;',
    yellow: yellow,
    green: green,
    blue: blue,
    purple: purple,
    grey: 'color grey;',
}
var consoleHistory = [];
var displayList = {
    ballstick: ballAndStick,
    backbone: backbone(50),
    cartoon: 'cartoons on;',
    hide: hide,
    mesh: 'meshRibbons on;',
    ribbons: 'ribbons on;',
    rockets: 'rockets on;',
    spacefill: spacefill,
    strand: 'strand on;',
    trace: trace(50),
    wireframe: wireframe(30),
};
var h = 0;
var labelList = {
    suggested: '',
    atoms: atomLabels('%i'),
    groups: customDefaultLabels,
}
var model = [];
var script = [];
var sessionBGC = sessionStorage.getItem("sessionBGC");
var sessionAA = sessionStorage.getItem("sessionAA");
var sessionSpeed = sessionStorage.getItem("sessionSpeed");
var sessionGestures = sessionStorage.getItem("sessionGestures");
var sessionToolbar = sessionStorage.getItem("sessionToolbar");
var userScript;

/////////////////////////////////////
//// Functions that set up html ////
///////////////////////////////////

function createFriendlyButtons() {
    $('#tutorialBox article button').each(function (index) {
        $(this).addClass('noPrint');
        $(this).prop('aria-hidden', true);
        var content = $(this).text();
        $(this).after('<span class="noScreen">' + content + '</span>');
    })
}
function showToolbar(boolean) {
    if (boolean === true) {
        $('#toolbar').removeClass('hideToolbar').addClass('showToolbar');
        $('#toolbarHandle').removeClass('handleIn').addClass('handleOut');
        $('#handleArrow').removeClass('arrowOut').addClass('arrowIn');
    }
    if (boolean === false) {
        $('#toolbar').removeClass('showToolbar').addClass('hideToolbar');
        $('#toolbarHandle').removeClass('handleOut').addClass('handleIn');
        $('#handleArrow').removeClass('arrowIn').addClass('arrowOut');
    }
}

/* Evaluation functions */
    function isSpinning() {
        return Jmol.evaluateVar(jsmolApplet, "_spinning");
    }

/* JSmol functions */
    function jsmol(script) {
        if(script !== undefined){
            Jmol.script(jsmolApplet, script);
        }
    }

/* Toolbar logic functions */
    function generalize() {
        //If no selection is user-defined, default to 'visible'
        if ($('#selectField input').is(':checked') === false) {
            jsmol(selectDisplayed);
        }
    }
    function halosLogic() {
        //If a select input is checked (or #selectAll is toggled on), turn halos on
        if ($('#selectAll').hasClass('toggled') === true || $('#selectField input').is(':checked') === true) {
            jsmol(halosOn);
        } else {
            jsmol(halosOff);
        }
    }

///////////////////////////
//// Toolbar functions ////
///////////////////////////

function bgSwatches() {
    //Give .swatch a background color from bgColorList
    for (var i = 0; i < bgColorList.length; i++) {
        var swatchID = String("#bgc" + (i + 1));
        $(swatchID).css("background-color", bgColorList[i]);
    };
};
function setBGC() {
    var bgColorVal = $(this).val();
    jsmol("set background " + bgColorList[bgColorVal] + ";");
}
function setLabels(input) {
    jsmol(defaultLabels);
    jsmol(labelList[input]);
}
function setSpeed() {
    jsmol('set platformSpeed ' + $('#speedSlider').val() + ';');
}
function resetSelectElems() {
    $('#selectAll').removeClass('toggled');
    $('#toolbar input:checkbox').prop('checked', false);
    $('#toolbar select').prop('selectedIndex', 0);
};
function userColor() {
    if ($('#colorCheckbox').is(':checked') === true) {
        generalize();
        jsmol(colorList[$('#colorOptions option:selected').val()])
    }
}
function userLabels() {
    if ($('#labelCheckbox').prop('checked') === true) {
        setLabels($('#labelOptions').val());
        $('#labelCheckbox').prop('checked', true);
        $('.labels').addClass('toggled');
    } else {
        jsmol(defaultLabels);
        $('#labelCheckbox').prop('checked', false);
        $('.labels').removeClass('toggled');
    }
}
function userDisplay() {
    if ($('#displayCheckbox').is(':checked') === true) {
        generalize();
        jsmol(resetDisplay + resetSurface + displayList[$('#displayOptions option:selected').val()]);
        //Since resetSurface removes halos, this adds them back in if needed
        halosLogic();
    }
}
function userSelect() {
    if ($('#selectAll').hasClass('toggled') === true) {
        $('#selectAll').removeClass('toggled')
    }
    if ($('#selectField input').is(':checked') === false) {
        jsmol(halosOff + selectClickable);
    } else {
        var element = '';
        var group = '';
        var motif = '';
        //Get values from options in reverse order
        if ($('#selectMotif').is(':checked') === true) {
            //If default option is selected (value=""), selection generalizes to displayed
            if ($('#motifOptions option:checked').val() === '') {
                motif = '(displayed)';
            } else {
                motif = '(' + $('#motifOptions option:checked').val() + ')';
            }
        }
        if ($('#selectGroup').is(':checked') === true) {
            //If default option is selected (value=""), selection generalizes to displayed
            if ($('#groupOptions > option:checked').val() === '') {
                group = '(displayed)';
            } else {
                group = '(' + $('#groupOptions option:checked').val() + ')';
            }
            //Adds ' and ' if previous option is not empty
            if (motif !== '') {
                group = group + ' and ';
            }
        }
        if ($('#selectElement').is(':checked') === true) {
            //If default option is selected (value=""), selection generalizes to displayed
            if ($('#elementOptions > option:checked').val() === '') {
                element = '(displayed)';
            } else {
                element = '(' + $('#elementOptions option:checked').val() + ')';
            }
            //Adds ' and ' if previous options are not empty
            if (group !== '' || motif !== '') {
                element = element + ' and ';
            }
        }
        var selection = element + group + motif;
        jsmol('userSelection = {' + selection + '};select @userSelection;');
        //Immediately writes display changes if display input is checked
        if ($('#displayCheckbox').is(':checked') === true) {
            userDisplay();
        }
        //Immediately writes color changes if color input is checked
        if ($('#colorCheckbox').is(':checked') === true) {
            userColor();
        }
        //Turns selection halos on or off to reflect state of select inputs
        halosLogic();
    }
}
function forceLabels(boolean) {
    if (boolean === true) {
        if (labelList.suggested === undefined || labelList.suggested === '') {
            setLabels('groups');
            $('#labelOptions').val('groups');
        } else {
            setLabels('suggested')
            $('#labelOptions').val('suggested');
        }
        $('#labelCheckbox').prop('checked', true);
        $('.labels').addClass('toggled');
    }
    if (boolean === false) {
        jsmol(selectAll + labelOff);
        $('#labelCheckbox').prop('checked', false);
        $('.labels').removeClass('toggled');
    }
};
function forceSpin(boolean) {
    if (boolean === true || boolean === false) {
        jsmol('spin ' + boolean + ';');
        if (boolean === false) {
            $('.spin').removeClass('toggled');
            $('#spinButton').removeClass('toggled');
        }
        if (boolean === true) {
            $('.spin').addClass('toggled');
            $('#spinButton').addClass('toggled');
        }
    }
};
function toggleAA() {
    if (Jmol.evaluateVar(jsmolApplet, "@antialiasDisplay") === true) {
        jsmol('set antialiasDisplay false;');
        $('#aaButton').removeClass('toggled');
    } else {
        jsmol("set antialiasDisplay true;");
        $('#aaButton').addClass('toggled');
    }
};
function toggleAll() {
    if ($('#selectAll').hasClass('toggled') === true) {
        $('#selectAll').removeClass('toggled');
        jsmol(selectAll + halosOff);
    } else {
        $('#selectAll').addClass('toggled');
        jsmol('userSelection = {*};select @userSelection;' + show + halosOn);
    }
    $('#selectField input').prop('checked', false);
}
function toggleLabels() {
    if ($('#labelCheckbox').prop('checked') === false) {
        forceLabels(true);
    } else {
        forceLabels(false);
    }
}

function toggleSpin() {
    if (isSpinning() === true) {
        forceSpin(false);
        userSpin = false;
    } else {
        forceSpin(true);
        userSpin = true;
    }
}
function toggleToolbar() {
    if ($('#toolbar').hasClass('hideToolbar') === true) {
        showToolbar(true);
    } else {
        showToolbar(false);
    }
    jsmol('refresh;');
}

/* Article functions */
    function baseModelName() {
        if (article[i].data instanceof Array) {
            return article[i].data[0].name;
        } else {
            return article[i].data.name;
        }
    }

    function decodeEntity(input){
        let doc = new DOMParser();
        return doc.parseFromString(input, "text/html").documentElement.textContent.toString()
    }

    function defaultSettings() {
        if (script['vars'] !== ''){
            $.globalEval(decodeEntity(script['vars']));
        }
        let config = eval(decodeEntity(script['config']));
        let display = eval(decodeEntity(script['display']));
        let labels = eval(decodeEntity(script['labels']));
        let camera = eval(decodeEntity(script['camera']));

        labelList.suggested = labels;
        $('article button').not('.noReset').removeClass('toggled');
        $('article input').not('.noReset').prop('checked', false);
        resetSelectElems();
        jsmol(quit + axesOff + defaultLabels + defaultSpin + echoOff + echoLoading('Processing...'));
        jsmol('set refreshing false;');
        if (config !== undefined) {
            jsmol(config);
        } else {
            jsmol(frame(1)+showHydrogens);
        }
        if (display !== null && display !== undefined) {
            jsmol(selectAll + show + resetDisplay + resetColor + resetSurface + display);
        }
        if (camera !== undefined) {
            jsmol('set refreshing TRUE;');
            jsmol(camera);
        } else {
            jsmol(selectVisible+orientSelected);
            jsmol('set refreshing TRUE;');
        }
        if (script['functions'] !== '') {
            eval(decodeEntity(script['functions']));
        }
        jsmol(echoReady(script['dataNavs'][0]['file']['title']));
    };
    function loadModels(dataNav) {
        model = dataNav;

        console.log(model);
        echoLoading('Loading model...');
        if (dataNav.length === 1) {


            jsmol('load ' + dataNav[0]['file']['path'] + ';' + 'set refreshing FALSE;');
            defaultSettings();
        } else {
            jsmol('set refreshing FALSE;');
            for (var n = 0; n < dataNav.length; n++) {
                if (n == 0) {
                    jsmol('load ' + dataNav[n]['file']['path'] + ';');
                } else {
                    jsmol('load APPEND ' + dataNav[n]['file']['path'] + ';');
                }
            }
            defaultSettings();
        }
    }

/* Function to be called from article buttons */
    function disable(id) {
        $(id).not('.ignore').prop('disabled', true);
        $(id).not('.ignore').addClass('disabled');
        $(id).not('.ignore').removeClass('toggled');
    };
    function enable(id) {
        $(id).not('.ignore').prop('disabled', false);
        $(id).not('.ignore').removeClass('disabled');
    };
    function enableToggle(element, enableTarget, disableTarget) {
        if ($(element).hasClass('toggled') === false) {
            disable(disableTarget);
        } else {
            enable(enableTarget);
        }
    };
    function pop(id) {
        $(id).remove();
    };
    function pseudoToggle(toggleOn, toggleOff) {
        $(toggleOff).removeClass('active');
        $(toggleOn).addClass('active');
    };
    function radioToggle(parent, children, onScript, offScript) {
        var $parent = $(parent);
        if ($parent.data('waschecked') == true) {
            $parent.prop('checked', false)
            $parent.data('waschecked', false);

            //Unchecks elements that have been identified as children of this radio button when parent is unchecked.
            $(children).prop('checked', false)
            disable(children);
            jsmol(offScript)
        }
        else {
            $parent.data('waschecked', true)
            enable(children);
            jsmol(onScript);
        }
        var $parentName = $(parent).attr('name');
        if ($parentName != undefined) {
            $('input[name="' + $parentName + '"').not($parent).data('waschecked', false);
        }
    };
    function toggle(element, on, off) {
        if ($(element).hasClass('active') === false) {
            jsmol(on);
            $(element).addClass('active');
        } else {
            jsmol(off);
            $(element).removeClass('active');
        }
    };
    function toggleState(element, state, newScript) {
        if ($(element).hasClass('toggled') === false) {
            jsmol('save state toggleWith'+state+';'+newScript);
            $(element).addClass('toggled');
        } else {
            jsmol('restore state toggleWith'+state+';');
            $(element).removeClass('toggled');
        }
};
    function turnOn(element, script1) {
        $(element).addClass('lockedON');
        jsmol(script1);
        $(element).prop('disabled', true);
};
    function verticalReveal(id) {
        //$(id).height($(id).height() + 0); Removed, now fixed in css keyframe 'verticalWipe' by adding 'to {height:100%}'
        $(id).removeClass('hide').addClass('verticalReveal');
        
};