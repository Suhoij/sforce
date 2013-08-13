/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='images/';

var fonts = {};


var resources = [
];
var symbols = {
"stage": {
   version: "1.0.0",
   minimumCompatibleVersion: "0.1.7",
   build: "1.0.0.185",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
         dom: [
         {
            id:'_3',
            type:'image',
            rect:['537px','11px','326px','254px','auto','auto'],
            fill:["rgba(0,0,0,0)",'images/9/_3.png','0px','0px']
         },
         {
            id:'_2',
            type:'image',
            rect:['270px','11px','327px','254px','auto','auto'],
            fill:["rgba(0,0,0,0)",'images/9/_2.png','0px','0px']
         },
         {
            id:'_1',
            type:'image',
            rect:['3px','11px','327px','254px','auto','auto'],
            fill:["rgba(0,0,0,0)",'images/9/_1.png','0px','0px']
         }],
         symbolInstances: [

         ]
      },
   states: {
      "Base State": {
         "${_Stage}": [
            ["color", "background-color", 'rgba(255,255,255,0.00)'],
            ["style", "width", '866px'],
            ["style", "height", '276px'],
            ["style", "overflow", 'hidden']
         ],
         "${__1}": [
            ["style", "top", '11px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "left", '3px'],
            ["style", "-webkit-transform-origin", [50,35], {valueTemplate:'@@0@@% @@1@@%'} ],
            ["style", "-moz-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "-ms-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "msTransformOrigin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "-o-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}]
         ],
         "${__2}": [
            ["style", "-webkit-transform-origin", [50,35], {valueTemplate:'@@0@@% @@1@@%'} ],
            ["style", "-moz-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "-ms-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "msTransformOrigin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "-o-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "background-position", [-331,0], {valueTemplate:'@@0@@px @@1@@px'} ],
            ["style", "left", '270px'],
            ["style", "top", '11px']
         ],
         "${__3}": [
            ["style", "top", '11px'],
            ["style", "background-position", [-331,0], {valueTemplate:'@@0@@px @@1@@px'} ],
            ["style", "left", '537px'],
            ["style", "-webkit-transform-origin", [50,35], {valueTemplate:'@@0@@% @@1@@%'} ],
            ["style", "-moz-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "-ms-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "msTransformOrigin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}],
            ["style", "-o-transform-origin", [50,35],{valueTemplate:'@@0@@% @@1@@%'}]
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 4000,
         autoPlay: true,
         timeline: [
            { id: "eid21", tween: [ "style", "${__2}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-331.000000,0.000000]}], position: 2000, duration: 1000, easing: "easeOutQuad" },
            { id: "eid11", tween: [ "transform", "${__1}", "scaleY", '1', { fromValue: '0'}], position: 1000, duration: 1000, easing: "easeOutQuad" },
            { id: "eid8", tween: [ "transform", "${__1}", "scaleX", '1', { fromValue: '0'}], position: 1000, duration: 1000, easing: "easeOutQuad" },
            { id: "eid25", tween: [ "style", "${__3}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-331.000000,0.000000]}], position: 3000, duration: 1000, easing: "easeOutQuad" }         ]
      }
   }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "EDGE-31128453");
