YAHOO.namespace("ovly.container");

function init() {
                        //YAHOO.ovly.container.overlay1 = new YAHOO.widget.Overlay("overlay1", {fixedcenter:true,visible:true,width:"600px"});
                        //YAHOO.ovly.container.overlay1.render();
            
                        YAHOO.ovly.container.overlay2 = new YAHOO.widget.Overlay("overlay2", {fixedcenter:true,visible:true,width:"600px"});
                        YAHOO.ovly.container.overlay2.render();
            
            
                        YAHOO.ovly.container.manoverlay0 = new YAHOO.widget.Panel("manoverlay0", {context:["hlptxt0","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay0.render();
            
                        YAHOO.ovly.container.manoverlay1 = new YAHOO.widget.Panel("manoverlay1", {context:["hlptxt1","tr","br",["beforeShow", "windowResize"],[5,15]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay1.render();
            
                        YAHOO.ovly.container.manoverlay2 = new YAHOO.widget.Panel("manoverlay2", {context:["hlptxt2","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay2.render();
            
                        YAHOO.ovly.container.manoverlay3 = new YAHOO.widget.Panel("manoverlay3", {context:["hlptxt3","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay3.render();
            
                        YAHOO.ovly.container.manoverlay4 = new YAHOO.widget.Panel("manoverlay4", {context:["hlptxt4","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay4.render();
            
                        YAHOO.ovly.container.manoverlay5 = new YAHOO.widget.Panel("manoverlay5", {context:["hlptxt5","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay5.render();
            
                        YAHOO.ovly.container.manoverlay6 = new YAHOO.widget.Panel("manoverlay6", {context:["hlptxt6","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay6.render();
            
                        YAHOO.ovly.container.manoverlay7 = new YAHOO.widget.Panel("manoverlay7", {context:["hlptxt7","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay7.render();
            
                        YAHOO.ovly.container.manoverlay8 = new YAHOO.widget.Panel("manoverlay8", {context:["hlptxt8","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay8.render();
            
                        YAHOO.ovly.container.manoverlay9 = new YAHOO.widget.Panel("manoverlay9", {context:["hlptxt9","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay9.render();
                        
                        YAHOO.ovly.container.manoverlay10 = new YAHOO.widget.Panel("manoverlay10", {context:["hlptxt10","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay10.render();
                        
                        YAHOO.ovly.container.manoverlay11 = new YAHOO.widget.Panel("manoverlay11", {context:["hlptxt11","tr","br",["beforeShow", "windowResize"],[5,5]],visible:false,width:"350px"});
                        YAHOO.ovly.container.manoverlay11.render();
            
                        YAHOO.ovly.container.manoverlayLEG = new YAHOO.widget.Panel("overlayLEG", {context:["hlptxtLEG","tr","br",["beforeShow", "windowResize"],[1,1]],visible:false,width:"250px"});
                        YAHOO.ovly.container.manoverlayLEG.render();
            
 
                        //YAHOO.util.Event.addListener("show1", "click", YAHOO.ovly.container.overlay1.show, YAHOO.ovly.container.overlay1, true);
                        //YAHOO.util.Event.addListener("hide1", "click", YAHOO.ovly.container.overlay1.hide, YAHOO.ovly.container.overlay1, true);
            
                        YAHOO.util.Event.addListener("show2", "click", YAHOO.ovly.container.overlay2.show, YAHOO.ovly.container.overlay2, true);
                        YAHOO.util.Event.addListener("hide2", "click", YAHOO.ovly.container.overlay2.hide, YAHOO.ovly.container.overlay2, true);
            
            
                        YAHOO.util.Event.addListener("showman0", "click", YAHOO.ovly.container.manoverlay0.show, YAHOO.ovly.container.manoverlay0, true);
                        YAHOO.util.Event.addListener("hideman0", "click", YAHOO.ovly.container.manoverlay0.hide, YAHOO.ovly.container.manoverlay0, true);
            
                        YAHOO.util.Event.addListener("showman1", "click", YAHOO.ovly.container.manoverlay1.show, YAHOO.ovly.container.manoverlay1, true);
                        YAHOO.util.Event.addListener("hideman1", "click", YAHOO.ovly.container.manoverlay1.hide, YAHOO.ovly.container.manoverlay1, true);
            
                        YAHOO.util.Event.addListener("showman2", "click", YAHOO.ovly.container.manoverlay2.show, YAHOO.ovly.container.manoverlay2, true);
                        YAHOO.util.Event.addListener("hideman2", "click", YAHOO.ovly.container.manoverlay2.hide, YAHOO.ovly.container.manoverlay2, true);
            
                        YAHOO.util.Event.addListener("showman3", "click", YAHOO.ovly.container.manoverlay3.show, YAHOO.ovly.container.manoverlay3, true);
                        YAHOO.util.Event.addListener("hideman3", "click", YAHOO.ovly.container.manoverlay3.hide, YAHOO.ovly.container.manoverlay3, true);
            
                        YAHOO.util.Event.addListener("showman4", "click", YAHOO.ovly.container.manoverlay4.show, YAHOO.ovly.container.manoverlay4, true);
                        YAHOO.util.Event.addListener("hideman4", "click", YAHOO.ovly.container.manoverlay4.hide, YAHOO.ovly.container.manoverlay4, true);
            
                        YAHOO.util.Event.addListener("showman5", "click", YAHOO.ovly.container.manoverlay5.show, YAHOO.ovly.container.manoverlay5, true);
                        YAHOO.util.Event.addListener("hideman5", "click", YAHOO.ovly.container.manoverlay5.hide, YAHOO.ovly.container.manoverlay5, true);
            
                        YAHOO.util.Event.addListener("showman6", "click", YAHOO.ovly.container.manoverlay6.show, YAHOO.ovly.container.manoverlay6, true);
                        YAHOO.util.Event.addListener("hideman6", "click", YAHOO.ovly.container.manoverlay6.hide, YAHOO.ovly.container.manoverlay6, true);
            
                        YAHOO.util.Event.addListener("showman7", "click", YAHOO.ovly.container.manoverlay7.show, YAHOO.ovly.container.manoverlay7, true);
                        YAHOO.util.Event.addListener("hideman7", "click", YAHOO.ovly.container.manoverlay7.hide, YAHOO.ovly.container.manoverlay7, true);
            
                        YAHOO.util.Event.addListener("showman8", "click", YAHOO.ovly.container.manoverlay8.show, YAHOO.ovly.container.manoverlay8, true);
                        YAHOO.util.Event.addListener("hideman8", "click", YAHOO.ovly.container.manoverlay8.hide, YAHOO.ovly.container.manoverlay8, true);
            
                        YAHOO.util.Event.addListener("showman9", "click", YAHOO.ovly.container.manoverlay9.show, YAHOO.ovly.container.manoverlay9, true);
                        YAHOO.util.Event.addListener("hideman9", "click", YAHOO.ovly.container.manoverlay9.hide, YAHOO.ovly.container.manoverlay9, true);
                        
                        YAHOO.util.Event.addListener("showman10", "click", YAHOO.ovly.container.manoverlay10.show, YAHOO.ovly.container.manoverlay10, true);
                        YAHOO.util.Event.addListener("hideman10", "click", YAHOO.ovly.container.manoverlay10.hide, YAHOO.ovly.container.manoverlay10, true);
                        
                        YAHOO.util.Event.addListener("showman11", "click", YAHOO.ovly.container.manoverlay11.show, YAHOO.ovly.container.manoverlay11, true);
                        YAHOO.util.Event.addListener("hideman11", "click", YAHOO.ovly.container.manoverlay11.hide, YAHOO.ovly.container.manoverlay11, true);
                        
                        YAHOO.util.Event.addListener("showLEG", "click", YAHOO.ovly.container.manoverlayLEG.show, YAHOO.ovly.container.manoverlayLEG, true);
                        YAHOO.util.Event.addListener("hideLEG", "click", YAHOO.ovly.container.manoverlayLEG.hide, YAHOO.ovly.container.manoverlayLEG, true);
}
	
YAHOO.util.Event.addListener(window, "load", init);