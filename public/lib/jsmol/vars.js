//Javascript functions and variables that return JSmol scripts
//Created by Ben Robinson (butterybikes@gmail.com). Last update 1/28/2020.

var frameNo;

function appendModel(modelName, frame) {
    var appendModel = 'load APPEND ../data/' + modelName + ';frame ' + frame + ';';
    return appendModel;
}

function backbone(float) {
    return 'backbone ' + float + ';';
}
function centerOn(atom) {
    return 'center {' + atom + '}; axes CENTER { ' + atom + ' };'
}
function color(color) {
    var color = 'color ' + color + ';'
    return color;
}
function colorAlpha(selector, color) {
    var colorAlpha = 'select (' + selector + ') and alpha;color ' + color + ';';
    return colorAlpha;
}
function colorStrucNo(scheme) {
    return 'color property strucno "' + scheme + '";';
}
function customSpacefill(value) {
    return 'spacefill ' + value + ';';
}
function customSpin(x, y, z) {
    return 'set spin x ' + x + ';set spin y ' + y + ';set spin z ' + z + ';';
}

function dBonds(value) {
    var dBonds = 'set showMultipleBonds ' + value + ';';
    return dBonds;
}
function echo(string, x, y, color, size, face, style, background) {
    return 'set echo ' + x + ' ' + y + ';color echo ' + color + ';font echo ' + size + ' ' + face + ' ' + style + ';echo ' + string + ';background echo ' + background + ';';
}
function echoLoading(text) {
    return echo(text, 'bottom', 'right', 'red', '18', 'sansSerif', 'plain', 'none');
}
function echoReady(text) {
    return echo(text, 'bottom', 'right', 'black', '18', 'sansSerif', 'plain', 'none');
}
function frame(int) {
    if (int !== 0){
        return 'frame ' + int + ';' + selectVisible + centerSelected + echoReady(model[int-1]['file']['title']);
    } else {
        return 'frame 0;' + selectVisible + centerSelected + echoReady(script['title']);
    }

}
function frameSwap(start, end , center) {
    if (frameNo === undefined) {
        frameNo = start + 1;
    } else {
        if (frameNo < end) {
            frameNo++;
        } else {
            frameNo = start;
        }
    }
    jsmol(frame(frameNo))
    if (center !== false) {
        jsmol(selectClickable + centerSelected)
    }
}
function hbonds(int, color) {
    if (color != null) {
        return 'set hbondsRasmol TRUE;calculate hbonds;hbonds ' + int + ';color hbonds ' + color + ';';
    } else {
        return 'set hbondsRasmol TRUE;calculate hbonds;hbonds ' + int + ';';
    }
}
function labelBuilder(target, text, color, font, scaling, group, alignment, offset, pointer, background) {
    var labelAlignment = '';
    var labelBackground = '';
    var labelColor = '';
    var labelFont = '';
    var labelGroup = '';
    var labelOffset = '';
    var labelPointer = '';
    var labelScaling = '';
    if (color != '') {
        labelColor = 'color labels ' + color + ';';
    }
    if (font != '') {
        labelFont = 'font labels ' + font + ';';
    }
    if (scaling != '') {
        labelScaling = 'set fontScaling ' + scaling + ';';
    }
    if (group != '') {
        if (group == 'front') {
            labelGroup = 'set labelFront ON;';
        } else if (group == 'atom') {
            labelGroup = 'set labelAtom ON;';
        } else if (group == 'group') {
            labelGroup = 'set labelGroup ON;';
        } else {
            labelGroup = 'set labelGroup ON ' + group + ';';
        }
    }
    if (alignment != '') {
        labelAlignment = 'set labelAlignment ' + alignment + ';';
    }
    if (offset != '') {
        if (offset.includes('{') === true) {
            labelOffset = 'set labelOffset ' + offset + ';';
        } else {
            labelOffset = 'set labelOffsetAbsolute ' + offset + ';';
        }
    }
    if (pointer != '') {
        labelPointer = 'set labelPointer ' + pointer + ';';
    }
    if (background != '') {
        labelBackground = 'background LABELS ' + background + ';';
    }
    return 'select ' + target + ';' + labelScaling + 'label ' + text + ';' + labelColor + labelFont + labelGroup + labelAlignment + labelOffset + labelPointer + labelBackground;
}

function loop(selector, rangeMin, rangeMax, func, delay) {
    var concat = '';
    var steps = 0;
    var range = rangeMax - rangeMin;
    if (range < 0) {
        steps = -range;
        for (var s = 0; s <= steps; s++) {
            concat = concat + 'select ' + selector + (rangeMin - s) + ';' + func + ' {selected};delay ' + delay + ';';
        };
    } else {
        steps = range;
        for (var s = 0; s <= steps; s++) {
            concat = concat + 'select ' + selector + (rangeMin + s) + ';' + func + ' {selected};delay ' + delay + ';';
        };
    }
    return concat;
}

function removeToggleNot(not) {
    $('article button').not(not).not('.ignore').removeClass('toggled');
}
function ribbons(int) {
    return 'ribbons ' + int + ';';
}
function runOnce(script) {
    return 'if (once == 0){' + script + 'once = 1;};';
}
function slab(int) {
    return 'slab on;slab ' + int + ';';
}
function select() {
    var selection = 'select none;'
    for (var a = 0; a < arguments.length; a++) {
        var select = 'select ADD ' + arguments[a] + ';';
        selection = selection + select;
    }
    return selection;
};
function selectOn(set) {
    return 'select on ' + set + ';';
}
function snake(residues, condition, display, delay) {
    for (r = 1; r <= residues; r++) {
        var segment = 'select ' + r + ' ' + condition + ';' + display + ';delay ' + delay + ';';
        jsmol(segment);
    }
};
function stepwise(selection, func, start, end, steps, delay) {
    var concat = 'select ' + selection + ';' + func + ' ' + start + ';';
    for (var s = 1; s <= steps; s++) {
        if (start > end) {
            var intVal = start - ((start + end) * (s / steps));
        }
        if (start < end) {
            var intVal = start + ((start + end) * (s / steps));
        }
        concat = concat + 'delay ' + delay + ';' + func + ' ' + intVal + ';';
    }
    return concat;
}
function strand(int) {
    return 'strand ' + int + ';';
}

function subset(exp) {
    return 'selectSet = {' + exp + '};display ' + exp + ';subset ' + exp + ';select all;center selected;';
};
function trace(float) {
    return 'trace ' + float + ';';
};
function wireframe(value) {
    return 'wireframe ' + value + ';';
};
function zap(selector) {
    var zap = 'zap ' + selector + ';';
    return zap;
};
function zoom(float) {
    return 'zoom ' + float + ';';
};

var animationOff = 'animation off;';
var animationOn = 'animation on;';
var axesOff = 'axes off;';
var axesOn = 'axes on;';
var ballAndStick = 'wireframe 0.15; spacefill 23%;';
var blue = 'color blue;';
var bottomView = 'moveto 1 {0 0 1000 0};'+ customSpin(0,0,10);
var cartoons = 'cartoons on;';
var centerSelected = 'center selected;axes CENTER {selected};';
var colorAcidic = 'select acidic; color red;';
var colorAmino = 'color amino;';
var colorBasic = 'select basic; color blue;';
var colorChain = 'color chain;';
var colorChainFriendly = 'color property chainNo "friendly";';
var colorFormalChrg = 'color formalCharge;';
var colorNeutral = 'select neutral;color gray;';
var colorPartialChrg = 'color partialCharge;';
var colorStructure = 'color structure;';
var colorTemp = 'color temperature;';
var cpk = 'color cpk;';
var cpkBalls = 'color balls cpk;';
var cyan = 'color cyan;';
var darkblue = 'color darkblue;';
var darkgoldenrod = 'color darkgoldenrod;';
var darkgreen = 'color darkgreen;';
var dBonds = 'set showMultipleBonds on;';
var defaultSpin = customSpin(0, 30, 0);
var delay1 = 'delay 1;';
var dipoleDelete = 'dipole DELETE;';
var defaultLabels = labelBuilder('*', 'off', 'cpk', '', '', '', '', '', '', 'none');
var deleteDraw = 'draw * delete;';
var deleteMeasures = 'measure DELETE;';
var displayAll = 'display all;';
var displayAndCenter = 'display selected;center selected;axes CENTER {selected};';
var displaySelected = 'display selected;';
var displayAddSelected = 'display ADD selected;';
var dotsOff = 'dots off;';
var dotsOn = 'dots on;';
var downYaxis = 'moveto 0 {1000 0 0 90};';
var downZaxis = 'moveto 0 {0 0 1 0};';
var echoOff = 'set echo off;';
var fadeIn = 'color translucent 1.0; delay 0.01; color translucent 0.9; delay 0.01; color translucent 0.8; delay 0.01; color translucent 0.7; delay 0.01; color translucent 0.6; delay 0.01; color translucent 0.5; delay 0.01; color translucent 0.4; delay 0.01; color translucent 0.3; delay 0.01; color translucent 0.2; delay 0.01; color translucent 0.1; delay 0.01; color translucent 0.0;';
var fadeOut = 'color translucent 0.1;delay 0.01;color translucent 0.2;delay 0.01;color translucent 0.3;delay 0.01;color translucent 0.4;delay 0.01;color translucent 0.5;delay 0.01;color translucent 0.6;delay 0.01;color translucent 0.7;delay 0.01;color translucent 0.8;delay 0.01;color translucent 0.9;delay 0.01;color translucent 1.0;';
var fiftyGray = 'color [x505050];';
var green = 'color green;';
var gold = 'color [255,200,0];';
var goldenrod = 'color goldenrod;';
var halosOff = 'selectionHalos OFF;';
var halosOn = 'selectionHalos ON;';
var hbondsOn = 'hbonds on;';
var hbondsOff = 'hbonds off;';
var helix = 'select helix;';
var hetero = 'select hetero;';
var heme = 'select hem;';
var hide = 'display REMOVE selected;';
var hideHydrogens = 'set showHydrogens false;';
var hideNotSelected = 'hide not selected;';
var hideSelected = 'hide selected;';
var hideMeasures = 'set showMeasurements false;';
var hydrogen = 'select hydrogen;';
var iron = 'select iron;';
var isosurfaceFlat = 'set transparent false;isosurface * on;';
var isosurfaceOff = 'isosurface * off;';
var isosurfaceOn = 'isosurface * on;';
var labelDisplay = 'label DISPLAY;';
var labelHide = 'label HIDE;';
var labelOff = 'label off;';
var labelOn = 'label on;';
var lcaoOff = 'lcaoCartoon off;lcaoCartoon LONEPAIR off;';
var lcaoOn = 'lcaoCartoon on;lcaoCartoon LONEPAIR on;';
var ligand = 'select ligand;';
var lightblue = 'color lightblue;';
var lime = 'color [x7FFF00];';
var magenta = 'color magenta;';
var mauve = 'color [130,130,210];';
var moveToSelected = 'moveTo 1.0 QUATERNION {selected};';
var notHydrogen = 'select not hydrogen;';
var nucleic = 'select nucleic;';
var opaqueSurface = 'isosurface opaque;';
var orange = 'color orange;';
var orientDisplayed = 'zoomTo 0 {displayed} 0;';
var orientSelected = 'zoomTo 0 {selected} 0;';
var oxygen = 'select oxygen;';
var pink = 'color pink;';
var protein = 'select protein;';
var proteinAndLigand = 'select protein or ligand;';
var purple = 'color purple;';
var quit = 'quit;';
var red = 'color red;';
var reset = 'reset;';
var resetBondColor = 'color bonds none;';
var royalblue = 'color royalblue;';
var sBonds = 'set showMultipleBonds off;';
var selectAll = 'select *;';
var selectAlpha = 'select alpha;';
var selectBackbone = 'select backbone;';
var selectCBs = 'select *.CB;';
var selectClickable = 'select clickable;'; //Selects atoms that are ACTUALLY visible (displayed in current frame)
var selectDisplayed = 'select displayed;'; //Selects atoms that are unambiguously in the displayed set
var selectHidden = 'select hidden;'; //Selects atoms that are unambiguously in the hidden set
var selectNone = 'select none;';
var selectNotSelected = 'select not selected;';
var selectSpine = 'select spine;';
var selectSubset = 'select @selectSet;';
var selectVisible = 'select visible;'; //Selects atoms that are visible in ANY way
var shapely = 'color shapely;'
var sheet = 'select sheet;';
var show = 'display add selected;';
var showHydrogens = 'set showHydrogens true;';
var showMeasures = 'set showMeasurements true;';
var sideView = 'moveto 1 {0 -707 707 180};' + customSpin(0, 10, 0);
var sidechain = 'select sidechain;';
var slabOff = 'slab off;';
var solvent = 'select solvent;';
var spacefill = 'var selectSet = {selected}; spacefill 100%; select @selectSet and not (protein or nucleic); wireframe on; select @selectSet and (protein or nucleic); trace on; select @selectSet;'; //At lowest platform speed, spacefilling models will still be visible
var spacefillOff = 'spacefill off;wireframe off;';
var spacefillOnly = 'spacefill only;';
var spinOn = 'spin on;';
var spinOff = 'spin off;';
var spinY360 = 'move 0 360 0 0 0 0 0 0 2;'
var subsetAll = 'subset all;'; //Adds all atoms back into subset (effectively cancelling subset)
var sulfur = 'select _S;';
var teal = 'color teal;';
var thickRibbons = 'set hermiteLevel -1;';
var translucentSurface = 'isosurface translucent;';
var topView = 'moveto 1 {0 -1000 0 179.64};' + customSpin(0,0,10);
var upYaxis = 'moveto 0 {-1000 18 9 90};';
var violet = 'color violet;';
var water = 'select water;';
var white = 'color white;';
var yellow = 'color yellow;';
var zoom0 = 'zoom 0;';
var zoomIn = 'zoomTo IN;';
var zoomToDisplayed = 'zoomTo 1.0 {displayed} 0;';
var zoomToSelected = 'zoomTo 1.0 {selected} 0;';
var zoomToVisible = 'zoomTo 1.0 {visible} 0;';
var zoomOut = 'zoomTo OUT;';
var zShadeOn = 'set zShade on;';
var zShadeOff = 'set zShade off;';

//Concatenated variables
function atomLabels(text) {
    return labelBuilder('*', text, '', '', 'on', 'atom', 'center', '', 'off', 'black');
}
var customDefaultLabels = labelBuilder('*.CA', '%n-%R:%c', 'amino', '', 'on', 'group', 'center', '', 'off', 'black') + labelBuilder('nucleic and *.P', '%n-%g:%c', 'shapely', '', 'on', 'group', 'center', '', 'off', 'black');
var sidechainLabels = labelBuilder('(purine and *.N9) or (pyrimidine and *.N1) or (protein and *.CB)', '%n-%R', 'shapely', '12px bold', 'off', 'group', '', '', '', 'white')

var resetBonds = 'set showMultipleBonds true;set ssbonds sidechain;';
var resetColor = cpk + resetBondColor + 'color balls none;';
var resetDisplay = 'backbone off;cartoon off;meshribbons off;ribbons off;rockets off;spacefill off;stars off;strand off;trace off;wireframe off;'; //Resets structural models only
var resetExtras = axesOff + hbondsOff + deleteDraw + deleteMeasures + slabOff + zShadeOff;
var resetSurface = 'dots off;' + halosOff + isosurfaceOff + lcaoOff;

var resetAll = selectAll + show + resetDisplay + resetColor + resetBonds + resetExtras + resetSurface;
var resetDisplayed = selectDisplayed + resetDisplay;