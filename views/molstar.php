<?php


?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .msp-plugin ::-webkit-scrollbar-thumb {
        background-color: #474748 !important;
    }
    .viewerSection {
        padding-top: 40px;
    }
    .controlsSection {
        width: 300px;
        float: left;
        padding: 40px 0 0 40px;
        margin-right: 30px;
    }
    .controlBox {
        border: 1px solid lightgray;
        padding: 10px;
        margin-bottom: 20px;
    }
    #myViewer{
        float:left;
        width:450px;
        height: 450px;
        position:relative;
    }
</style>

<div id="myViewer"></div>

<?php
 array_push($this->scripts,
    "<script type='text/javascript' src='https://www.ebi.ac.uk/pdbe/pdb-component-library/js/pdbe-molstar-plugin-3.0.0.js'></script>",
            "<script>
                    //Create plugin instance
                    var viewerInstance = new PDBeMolstarPlugin();
            
                    //Set options (Checkout available options list in the documentation)
                    var options = {
                        moleculeId: '1cbs',
                        // expanded: true,
                        // loadMaps: true,
                        // bgColor: {r:255, g:255, b:255},
                        hideControls: true,
                        domainAnnotation: true,
                        validationAnnotation: true,
                        // subscribeEvents: true,
                        hideCanvasControls: ['expand', 'selection', 'animation', 'controlToggle', 'controlInfo'],
                        landscape: true
                    }
            
                    //Ligand view
                    // var options = {
                    //   moleculeId: '1cbs',
                    //   ligandView: {label_comp_id: 'REA'},
                    //   loadMaps: true,
                    //   expanded: false,
                    //   hideControls: true,
                    //   bgColor: {r:255, g:255, b:255},
                    // }
            
                    //Superposition options
                    // var options = {
                    //   moleculeId: 'P08083',
                    //   superposition: true,
                    //   expanded: true,
                    //   // hideControls: true,
                    //   bgColor: {r:255, g:255, b:255},
                    //   superpositionParams: { matrixAccession: 'P08083' }
                    // }
            
                    // // AF
                    // var options = {
                    //   customData: {
                    //     url: 'https://alphafold.ebi.ac.uk/files/AF-O15552-F1-model_v1.cif',
                    //     format: 'cif'
                    //   },
                    //   bgColor: {r:255, g:255, b:255},
                    //   // hideControls: true,
                    //   alphafoldView: true,
                    //   hideCanvasControls: ['selection', 'animation', 'controlToggle', 'controlInfo']
                    // }
            
                    //Get element from HTML/Template to place the viewer
                    var viewerContainer = document.getElementById('myViewer');
            
                    //Call render method to display the 3D view
                    viewerInstance.render(viewerContainer, options);
            
                // document.addEventListener('PDB.molstar.mouseover', (e) => {
                //   //do something on event
                //   console.log(e)
                // });
            </script>"
 );

?>

