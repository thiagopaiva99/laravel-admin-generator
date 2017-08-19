					var tpj=jQuery;					
					var revapi116;
					tpj(document).ready(function() {
						if(tpj("#rev_slider_4_1").revolution == undefined){
							revslider_showDoubleJqueryError("#rev_slider_4_1");
						}else{
							revapi116 = tpj("#rev_slider_4_1").show().revolution({
								sliderType:"standard",
								jsFileLocation:"../revolution/js/",
								sliderLayout:"auto",
								dottedOverlay:"none",
								delay:3000,
	                            navigation: {
	                                keyboardNavigation: "on",
	                                keyboard_direction: "horizontal",
	                                mouseScrollNavigation: "off",
	                                onHoverStop: "off",
	                                touch: {
	                                    touchenabled: "on",
	                                    swipe_threshold: 75,
	                                    swipe_min_touches: 1,
	                                    swipe_direction: "horizontal",
	                                    drag_block_vertical: false
	                                },
	                                arrows: {
	                                    style: "erinyen",
	                                    enable: false,
	                                    hide_onmobile: true,
	                                    hide_onleave: false,
	                                    tmp: '<div class="tp-title-wrap">	<span class="tp-arr-titleholder">{{title}}</span>    <span class="tp-arr-imgholder"></span> </div>',
	                                    left: {
	                                        h_align: "left",
	                                        v_align: "bottom",
	                                        h_offset: 10,
	                                        v_offset: 250
	                                    },
	                                    right: {
	                                        h_align: "right",
	                                        v_align: "bottom",
	                                        h_offset: 10,
	                                        v_offset: 250
	                                    }
	                                },
	                            },
								viewPort: {
									enable:true,
									outof:"pause",
									visible_area:"80%"
								},
								gridwidth:1240,
								gridheight:630,
								lazyType:"none",
								shadow:0,
								spinner:"off",
								stopLoop:"off",
								stopAfterLoops:-1,
								stopAtSlide:-1,
								shuffle:"off",
								autoHeight:"off",
								hideThumbsOnMobile:"off",
								hideSliderAtLimit:0,
								hideCaptionAtLimit:0,
								hideAllCaptionAtLilmit:0,
								debugMode:false,
								fallbacks: {
									simplifyAll:"off",
									nextSlideOnWindowFocus:"off",
									disableFocusListener:false,
								}
							});
						}
					});	/*ready*/
