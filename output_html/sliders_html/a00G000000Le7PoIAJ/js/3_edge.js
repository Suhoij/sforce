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
            id:'_1',
            type:'image',
            rect:['97px','7px','579px','9px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/_1.png",'0px','0px']
         },
         {
            id:'_2',
            type:'image',
            rect:['102px','85px','574px','9px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/_2.png",'0px','0px']
         },
         {
            id:'_3',
            type:'image',
            rect:['107px','163px','569px','9px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/_3.png",'0px','0px']
         },
         {
            id:'_4',
            type:'image',
            rect:['111px','239px','565px','9px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/_4.png",'0px','0px']
         },
         {
            id:'_5',
            type:'image',
            rect:['112px','316px','564px','43px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/_5.png",'0px','0px']
         },
         {
            id:'s1',
            type:'image',
            rect:['170px','303px','65px','16px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/s1.png",'0px','0px']
         },
         {
            id:'s2',
            type:'image',
            rect:['273px','245px','65px','74px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/s2.png",'0px','0px']
         },
         {
            id:'s3',
            type:'image',
            rect:['379px','168px','65px','151px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/s3.png",'0px','0px']
         },
         {
            id:'s4',
            type:'image',
            rect:['479px','91px','65px','228px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/s4.png",'0px','0px']
         },
         {
            id:'s5',
            type:'image',
            rect:['583px','11px','65px','308px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/s5.png",'0px','0px']
         },
         {
            id:'strelka',
            type:'image',
            rect:['45px','6px','37px','312px','auto','auto'],
            clip:['rect(318px 37px 312px 0px)'],
            fill:["rgba(0,0,0,0)",im+"3/strelka.png",'0px','0px']
         },
         {
            id:'text',
            type:'image',
            rect:['11px','111px','15px','111px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"3/text.png",'0px','0px']
         }],
         symbolInstances: [

         ]
      },
   states: {
      "Base State": {
         "${_strelka}": [
            ["style", "top", '6px'],
            ["style", "left", '45px'],
            ["style", "clip", [318,37,312,0], {valueTemplate:'rect(@@0@@px @@1@@px @@2@@px @@3@@px)'} ]
         ],
         "${__4}": [
            ["style", "top", '239px'],
            ["style", "opacity", '0'],
            ["style", "left", '111px']
         ],
         "${__3}": [
            ["style", "top", '163px'],
            ["style", "opacity", '0'],
            ["style", "left", '107px']
         ],
         "${__2}": [
            ["style", "top", '85px'],
            ["style", "opacity", '0'],
            ["style", "left", '102px']
         ],
         "${__5}": [
            ["style", "top", '316px'],
            ["style", "opacity", '0'],
            ["style", "left", '112px']
         ],
         "${_text}": [
            ["style", "top", '111px'],
            ["style", "left", '11px'],
            ["style", "background-position", [-18,0], {valueTemplate:'@@0@@px @@1@@px'} ]
         ],
         "${__1}": [
            ["style", "top", '7px'],
            ["style", "opacity", '0'],
            ["style", "left", '97px']
         ],
         "${_s4}": [
            ["style", "top", '91px'],
            ["style", "left", '479px'],
            ["style", "background-position", [0,233], {valueTemplate:'@@0@@px @@1@@px'} ]
         ],
         "${_s2}": [
            ["style", "top", '245px'],
            ["style", "left", '273px'],
            ["style", "background-position", [0,79], {valueTemplate:'@@0@@px @@1@@px'} ]
         ],
         "${_Stage}": [
            ["color", "background-color", 'rgba(255,255,255,0.00)'],
            ["style", "width", '686px'],
            ["style", "height", '372px'],
            ["style", "overflow", 'hidden']
         ],
         "${_s3}": [
            ["style", "top", '168px'],
            ["style", "left", '379px'],
            ["style", "background-position", [0,156], {valueTemplate:'@@0@@px @@1@@px'} ]
         ],
         "${_s1}": [
            ["style", "top", '303px'],
            ["style", "left", '170px'],
            ["style", "background-position", [0,22], {valueTemplate:'@@0@@px @@1@@px'} ]
         ],
         "${_s5}": [
            ["style", "top", '11px'],
            ["style", "left", '583px'],
            ["style", "background-position", [0,314], {valueTemplate:'@@0@@px @@1@@px'} ]
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 3000,
         autoPlay: true,
         timeline: [
            { id: "eid40", tween: [ "style", "${_strelka}", "clip", [0,37,312,0], { valueTemplate: 'rect(@@0@@px @@1@@px @@2@@px @@3@@px)', fromValue: [318,37,312,0]}], position: 1000, duration: 2000, easing: "easeInQuad" },
            { id: "eid18", tween: [ "style", "${_text}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-18.000000,0.000000]}], position: 200, duration: 400 },
            { id: "eid11", tween: [ "style", "${__4}", "opacity", '1', { fromValue: '0.000000'}], position: 600, duration: 200 },
            { id: "eid8", tween: [ "style", "${__3}", "opacity", '1', { fromValue: '0'}], position: 400, duration: 200 },
            { id: "eid6", tween: [ "style", "${__2}", "opacity", '1', { fromValue: '0.000000'}], position: 200, duration: 200 },
            { id: "eid15", tween: [ "style", "${__5}", "opacity", '1', { fromValue: '0'}], position: 800, duration: 200 },
            { id: "eid36", tween: [ "style", "${_s5}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0.000000,314]}], position: 2600, duration: 400, easing: "easeOutQuad" },
            { id: "eid33", tween: [ "style", "${_s4}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0.000000,233]}], position: 2200, duration: 400, easing: "easeOutQuad" },
            { id: "eid3", tween: [ "style", "${__1}", "opacity", '1', { fromValue: '0.000000'}], position: 0, duration: 200 },
            { id: "eid24", tween: [ "style", "${_s1}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0.000000,22]}], position: 1000, duration: 400, easing: "easeOutQuad" },
            { id: "eid30", tween: [ "style", "${_s3}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0.000000,156]}], position: 1800, duration: 400, easing: "easeOutQuad" },
            { id: "eid27", tween: [ "style", "${_s2}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0.000000,79]}], position: 1400, duration: 400, easing: "easeOutQuad" }         ]
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
})(jQuery, AdobeEdge, "EDGE-20494557");
