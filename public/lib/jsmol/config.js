Jmol._isAsync = false;

var jsmolApplet;

var s = document.location.search;
Jmol._debugCode = (s.indexOf("debugcode") >= 0);

/* Initial functions */
function initialBGC() {
    if (sessionBGC !== null) {
        $('.swatch input[value=' + sessionBGC + ']').prop('checked', true);
        jsmol("set background " + bgColorList[sessionBGC] + ";");
    } else {
        $('.swatch input[value="2"]').prop('checked', true);
        jsmol("set background " + bgColorList[0] + ";");
    }
}
function initialAA() {
    if (sessionAA === 'false') {
        $('#aaButton').removeClass('toggled');
        jsmol('set antialiasDisplay false;');
    } else {
        $('#aaButton').removeClass('toggled');
        jsmol('set antialiasDisplay false;');
    }
}
function initialSpeed() {
    if (sessionSpeed === null) {
        let speed = 2;
        $('#speedSlider').val(speed);
        jsmol('set platformSpeed ' + speed + ';');
    } else {
        $('#speedSlider').val(sessionSpeed);
        jsmol('set platformSpeed ' + sessionSpeed + ';');
    }
}
function sessionSettings() {
    initialBGC();
    initialAA();
    initialSpeed();
}
let setupJSmol = function () {
    var settings = {
        bind: ['"SHIFT-LEFT-DRAG" "_wheelZoom"'],
        frank: 'off',
        measureUnits: 'angstroms',
        picking: 'MEASURE ANGLE',
        unbind: ['"_clickFrank"','"_rotateZorZoom"',],
        zoomLarge: 'false',
    }
    var bindings = '';
    for (var b = 0; b < settings.bind.length; b++) {
        bindings = bindings + 'bind ' + settings.bind[b] + ';';
    }
    var unbindings = '';
    for (var u = 0; u < settings.unbind.length; u++) {
        unbindings = unbindings + 'unbind ' + settings.unbind[u] + ';';
    }
    jsmol('set disablePopupMenu false;set allowGestures true;set frank ' + settings.frank + ';set zoomLarge ' + settings.zoomLarge + ';set measurementUnits ' + settings.measureUnits + ';' + unbindings + bindings + 'set picking ' + settings.picking + ';');
}
let initialize = function () {
    sessionSettings();
    setupJSmol();
    loadModels(script['dataNavs']);
}

/* JSmol Info object */
let Info = {
	width: "100%",
	height: "100%",
	debug: false,
	color: "0xFFFFFF",
	addSelectionOptions: false,
	use: "HTML5",
	j2sPath: "/lib/jsmol/j2s",
    jarPath: "/lib/jsmol/java",
	jarFile: "JmolAppletSigned.jar",
	isSigned: true,
    readyFunction: initialize,
    script: echoLoading("Loading model..."),
    serverURL: "/lib/jsmol/php/jsmol.php",
	disableJ2SLoadMonitor: true,
    disableInitialConsole: true,
    allowJavaScript: true,
    language: "en",
    zIndexBase: 500,
};

$('#jsmolApplet').html(Jmol.getAppletHtml('jsmolApplet', Info));

let lastPrompt=0;

