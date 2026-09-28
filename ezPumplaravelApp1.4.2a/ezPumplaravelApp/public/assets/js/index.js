$(function() {
    "use strict";
	
	// chart 1
	$('#chart1').sparkline([5,8,7,10,9,10,8,6,4,6,8,7,6,8], {
            type: 'bar',
            height: '35',
            barWidth: '3',
            resize: true,
            barSpacing: '3',
            barColor: '#fff'
        });

     
    // chart 2
    $("#chart2").sparkline([0,5,3,7,5,10,3,6,5,10], {
            type: 'line',
            width: '80',
            height: '40',
            lineWidth: '2',
            lineColor: '#fff',
            fillColor: 'transparent',
            spotColor: '#fff',
        })
		
	
	// chart 3
     $("#chart3").sparkline([2,3,4,5,4,3,2,3,4,5,6,5,4,3,4,5], {
        type: 'discrete',
        width: '75',
        height: '40',
        lineColor: '#fff',
        lineHeight: 22

     });
	 
	 

	// chart 4	
	$("#chart4").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
		type: 'line',
		width: '100',
		height: '25',
		lineWidth: '2',
		lineColor: '#ffffff',
		fillColor: 'transparent'
		
	});
	

  
// chart 5

      var ctx = document.getElementById('chart5').getContext('2d');

      var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke1.addColorStop(0, '#17ead98f');
      gradientStroke1.addColorStop(1, '#6078eaa3');

      var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke2.addColorStop(0, '#ef431e87');
      gradientStroke2.addColorStop(1, '#ef431e87');

      var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke3.addColorStop(0, '#15ca203d');
      gradientStroke3.addColorStop(1, '#15ca203d');

      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7, 8],
          datasets: [{
            label: 'ULTRA SUPER',
            data: [0, 30, 60, 25, 60, 25, 50, 0],
            pointBorderWidth: 2,
            pointBackgroundColor: 'transparent',
			pointHoverBackgroundColor: gradientStroke1,
            backgroundColor: gradientStroke1,
            borderColor: gradientStroke1,
            borderWidth: 2
          }, {
            label: 'HI OCTANE',
            data: [0, 60, 25, 80, 35, 75, 30, 0],
            pointBorderWidth: 2,
            pointBackgroundColor: 'transparent',
            pointHoverBackgroundColor: gradientStroke3,
            backgroundColor: gradientStroke3,
            borderColor: gradientStroke3,
            borderWidth: 2


          }
          , {
            label: 'POWER DIESEL',
            data: [0, 90, 33, 70, 35, 90, 30, 0],
            pointBorderWidth: 2,
            pointBackgroundColor: 'transparent',
			pointHoverBackgroundColor: gradientStroke2,
            backgroundColor: gradientStroke2,
            borderColor: gradientStroke2,
            borderWidth: 2
          }
        ]
        },
        
        options: {
          maintainAspectRatio: false,
          legend: {
            position: 'bottom',
                  display: true,
            labels: {
                    boxWidth:10
                  }
                },
                
            tooltips: {
			  displayColors:true
            }

         }
      });

   // chart 6a
    var ctx = document.getElementById("chart6a").getContext('2d');
   
     var gradientStroke3a = ctx.createLinearGradient(0, 0, 0, 300);
     gradientStroke3a.addColorStop(0, '#17ead9');
     gradientStroke3a.addColorStop(1, '#6078ea');

      var gradientStroke4a = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke4a.addColorStop(0, '#f80759');
      gradientStroke4a.addColorStop(0.5, '#bc4e9c');

      var gradientStroke5a = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke5a.addColorStop(0, '#15ca20');
      gradientStroke5a.addColorStop(1, '#004804');

      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
          datasets: [{
            label: 'Price',
            data: [8, 4, 2, 7, 5, 1, 3, 6, 9, 0, 8, 5, 7, 6, 3, 4, 5, 1, 2, 9, 8, 7, 3, 4, 6, 0, 9, 1, 2, 7],
            borderColor: gradientStroke3a,
            backgroundColor: gradientStroke3a,
            hoverBackgroundColor: gradientStroke3a,
            pointRadius: 0,
            fill: false,
            borderWidth: 1
          }, {
            label: 'Quantity',
            data: [6, 3, 1, 8, 5, 9, 0, 4, 7, 2, 6, 4, 8, 3, 1, 0, 9, 2, 7, 5, 6, 1, 3, 8, 2, 5, 0, 9, 7, 4],
            borderColor: gradientStroke4a,
            backgroundColor: gradientStroke4a,
            hoverBackgroundColor: gradientStroke4a,
            pointRadius: 0,
            fill: false,
            borderWidth: 1
          }]
        },
		options:{
      maintainAspectRatio: false,
		  legend: {
			  position: 'bottom',
              display: true,
			  labels: {
                boxWidth:12
              }
            },
			tooltips: {
			  displayColors:false,
			},	
		  scales: {
			  xAxes: [{
				barPercentage: .5
			  }]
		     }
		}
      });
	  
      var ctx = document.getElementById("chart6").getContext('2d');
   
      var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke3.addColorStop(0, '#42e695');
      gradientStroke3.addColorStop(1, '#3bb2b8');

      var gradientStroke4 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke4.addColorStop(0, ' #7f00ff');
      gradientStroke4.addColorStop(0.5, '#e100ff');

      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7, 8],
          datasets: [{
            label: 'Laptops',
            data: [40, 30, 60, 35, 60, 25, 50, 40],
            borderColor: gradientStroke3,
            backgroundColor: gradientStroke3,
            hoverBackgroundColor: gradientStroke3,
            pointRadius: 0,
            fill: false,
            borderWidth: 1
          }, {
            label: 'Mobiles',
            data: [50, 60, 40, 70, 35, 75, 30, 20],
            borderColor: gradientStroke4,
            backgroundColor: gradientStroke4,
            hoverBackgroundColor: gradientStroke4,
            pointRadius: 0,
            fill: false,
            borderWidth: 1
          }]
        },
		options:{
      maintainAspectRatio: false,
		  legend: {
			  position: 'bottom',
              display: true,
			  labels: {
                boxWidth:12
              }
            },
			tooltips: {
			  displayColors:false,
			},	
		  scales: {
			  xAxes: [{
				barPercentage: .5
			  }]
		     }
		}
      });
	  
	  
	  // chart 2
	var optionsLine = {
		chart: {
			foreColor: '#9ba7b2',
			height: 360,
			type: 'line',
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: true,
				top: 3,
				left: 2,
				blur: 4,
				opacity: 0.1,
			}
		},
		stroke: {
			curve: 'smooth',
			width: 5
		},
		colors: ["#0d6efd", '#212529'],
		series: [{
			name: "Music",
			data: [1, 15, 56, 20, 33, 27]
		}, {
			name: "Photos",
			data: [30, 33, 21, 42, 19, 32]
		}],
		title: {
			text: 'Multiline Chart',
			align: 'left',
			offsetY: 25,
			offsetX: 20
		},
		subtitle: {
			text: 'Statistics',
			offsetY: 55,
			offsetX: 20
		},
		markers: {
			size: 4,
			strokeWidth: 0,
			hover: {
				size: 7
			}
		},
		grid: {
			show: true,
			padding: {
				bottom: 0
			}
		},
		labels: ['01/15/2002', '01/16/2002', '01/17/2002', '01/18/2002', '01/19/2002', '01/20/2002'],
		xaxis: {
			tooltip: {
				enabled: false
			}
		},
		legend: {
			position: 'top',
			horizontalAlign: 'right',
			offsetY: -20
		}
	}
	var chartLine = new ApexCharts(document.querySelector('#chart2222'), optionsLine);
	chartLine.render();
	
	  
  // chart 7
 var ctx = document.getElementById('chart7').getContext('2d');

  var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke1.addColorStop(0, '#ee0979');
      gradientStroke1.addColorStop(1, '#ff6a00');

      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7],
          datasets: [{
            label: 'Views',
            data: [3, 30, 10, 10, 22, 12, 5],
            pointBorderWidth: 2,
            pointHoverBackgroundColor: gradientStroke1,
            backgroundColor: gradientStroke1,
            borderColor: 'transparent',
            borderWidth: 1
          }]
        },
        options: {
          maintainAspectRatio: false,
            legend: {
			  position: 'bottom',
              display:false
            },
            tooltips: {
			  displayColors:false,	
              mode: 'nearest',
              intersect: false,
              position: 'nearest',
              xPadding: 10,
              yPadding: 10,
              caretPadding: 10
            },
			scales: {
			  xAxes: [{
				ticks: {
                    beginAtZero:true
                },
				gridLines: {
				  display: true 
				},
			  }],
			   yAxes: [{
				ticks: {
                    beginAtZero:true
                },
				gridLines: {
				  display: true 
				},
			  }]
		     }

         }
      });  
	  
	  
	  

// world map

jQuery('#dashboard-map').vectorMap(
{
    map: 'world_mill_en',
    backgroundColor: 'transparent',
    borderColor: '#818181',
    borderOpacity: 0.25,
    borderWidth: 1,
    zoomOnScroll: false,
    color: '#009efb',
    regionStyle : {
        initial : {
          fill : '#15ca20'
        }
      },
    markerStyle: {
      initial: {
                    r: 9,
                    'fill': '#fff',
                    'fill-opacity':1,
                    'stroke': '#000',
                    'stroke-width' : 5,
                    'stroke-opacity': 0.4
                },
                },
    enableZoom: true,
    hoverColor: '#009efb',
    markers : [{
        latLng : [21.00, 78.00],
        name : 'I Love My India'
      
      }],
    hoverOpacity: null,
    normalizeFunction: 'linear',
    scaleColors: ['#b6d6ff', '#005ace'],
    selectedColor: '#c9dfaf',
    selectedRegions: [],
    showTooltip: true
});



  // chart 8
  $("#chart8").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#dd4b39',
            fillColor: 'rgba(221, 75, 57, 0.5)',
            spotColor: '#dd4b39',
    }); 
	
	// chart 9
	$("#chart9").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#3b5998',
            fillColor: 'rgba(59, 89, 152, 0.5)',
            spotColor: '#3b5998',
    });
	
	
	// chart 10
	$("#chart10").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#55acee',
            fillColor: 'rgba(85, 172, 238, 0.5)',
            spotColor: '#55acee',
    });
	
	
	// chart 11
	$("#chart11").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#0976b4',
            fillColor: 'rgba(9, 118, 180, 0.5)',
            spotColor: '#0976b4',
    });
	
	
	// chart 12
	$("#chart12").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#1769ff',
            fillColor: 'rgba(23, 105, 255, 0.5)',
            spotColor: '#1769ff',
    });
	
	
	// chart 13
	$("#chart13").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#ea4c89',
            fillColor: 'rgba(234, 76, 137, 0.5)',
            spotColor: '#ea4c89',
    });
	
	
	// chart 14
	$("#chart14").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#333333',
            fillColor: 'rgba(51, 51, 51, 0.5)',
            spotColor: '#333333',
    });

	
   // chart 15
   $('#chart15').sparkline([5,8,7,10,9,10,8,6,4,6,8,7,6,8,10,8,6,0], {
            type: 'bar',
            width: '150',
            height: '45',
            barWidth: '3',
            resize: true,
            barSpacing: '5',
            barColor: '#fff'
        });	
	

  // chart 16
  var ctx = document.getElementById("chart16").getContext('2d');

    var gradientStroke5 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke5.addColorStop(0, '#7f00ff');
      gradientStroke5.addColorStop(1, '#e100ff');

      var gradientStroke6 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke6.addColorStop(0, '#fc4a1a');
      gradientStroke6.addColorStop(1, '#f7b733');


      var gradientStroke7 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke7.addColorStop(0, '#283c86');
      gradientStroke7.addColorStop(1, '#45a247');

      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ["Samsung", "Apple", "Nokia"],
          datasets: [{
            backgroundColor: [
              gradientStroke5,
              gradientStroke6,
              gradientStroke7
            ],

             hoverBackgroundColor: [
              gradientStroke5,
              gradientStroke6,
              gradientStroke7
            ],

            data: [50, 50, 50]
          }]
        },
        options: {
          maintainAspectRatio: false,
            legend: {
              display: false
            },
            tooltips: {
			  displayColors:false
            }
        }
      });


	  
// chart 17
var ctx = document.getElementById("chart17").getContext('2d');

  var gradientStroke8 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke8.addColorStop(0, '#42e695');
      gradientStroke8.addColorStop(1, '#3bb2b8');

      var gradientStroke9 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke9.addColorStop(0, '#4776e6');
      gradientStroke9.addColorStop(1, '#8e54e9');


      var gradientStroke10 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke10.addColorStop(0, '#ee0979');
      gradientStroke10.addColorStop(1, '#ff6a00');

      var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
          labels: ["Gross Profit", "Revenue", "Expense"],
          datasets: [{
            backgroundColor: [
              gradientStroke8,
              gradientStroke9,
              gradientStroke10
            ],

             hoverBackgroundColor: [
              gradientStroke8,
              gradientStroke9,
              gradientStroke10
            ],
            data: [5, 8, 7]
          }]
        },
        options: {
          maintainAspectRatio: false,
            legend: {
              display: false
            },
            tooltips: {
			  displayColors:false
            }
        }
      });


// chart 18
var ctx = document.getElementById("chart18").getContext('2d');

    var gradientStroke11 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke11.addColorStop(0, '#ba8b02');
      gradientStroke11.addColorStop(1, '#181818');

      var gradientStroke12 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke12.addColorStop(0, '#2c3e50');
      gradientStroke12.addColorStop(1, '#fd746c');


      var gradientStroke13 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke13.addColorStop(0, '#ff0099');
      gradientStroke13.addColorStop(1, '#493240');

      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ["Jeans", "T-Shirts", "Shoes"],
          datasets: [{
            backgroundColor: [
              gradientStroke11,
              gradientStroke12,
              gradientStroke13
            ],
            hoverBackgroundColor: [
              gradientStroke11,
              gradientStroke12,
              gradientStroke13
            ],
            data: [25, 25, 25]
          }]
        },
        options: {
          maintainAspectRatio: false,
            legend: {
              display: false
            },
            tooltips: {
			  displayColors:false
            }
        }
      });
	  
	  
	  
});


 // Index Notification
	 
	 function info_noti(){
		Lobibox.notify('default', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		size: 'mini',
		position: 'top right',
		icon: 'bx bx-check-circle',
		msg: 'EzPump Login Successfully.'
		});
	  } 