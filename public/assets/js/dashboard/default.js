
// second chart dashbord dafault
var options17 = {
  series: [6, 67, 61, 90],
  chart: {
      height: 380,
      type: 'radialBar',
  },
  plotOptions: {
      radialBar: {
          offsetY: 0,
          startAngle: 0,
          endAngle: 270,
          hollow: {
              margin: 5,
              size: '30%',
              background: 'transparent',
              image: undefined,
          },
          dataLabels: {
              name: {
                  show: false,
              },
              value: {
                  show: false,
              }
          }
      }
  },
  colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary, vihoAdminConfig.primary, vihoAdminConfig.secondary],
  labels: ['Total order', 'Total product', 'Quantity', 'Page views'],
  legend: {
      show: true,
      floating: true,
      fontSize: '14px',
      position: 'left',
      fontFamily: 'Roboto',
      fontweight: 400,
      // offsetX:30,
      offsetY: 20,
      labels: {
          useSeriesColors: true,
      },
      markers: {
          size: 0,
          show: false,
      },
      formatter: function(seriesName, opts) {
          return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
      },
      itemMargin: {
          vertical: 5,
          horizontal: 2
      }
  },
  stroke: {
      lineCap: 'round'
  },
  responsive: [{
      breakpoint: 480,
      options: {
          legend: {
              show: true,
              fontSize: '10px',
          }
      }
  }]
};
// var chart17 = new ApexCharts(document.querySelector("#chart-dashbord"), options17);
// chart17.render();
// chart-4 dashbord
var options21 = {
  series: [{
      name: 'series1',
      data: [90, 78, 90, 84, 94, 60, 95, 88, 100]
  }],
  chart: {
      height: 405,
      type: 'area',
      toolbar: {
          show: false
      }
  },
  dataLabels: {
      enabled: false
  },
  stroke: {
      curve: 'smooth'
  },
  xaxis: {
      type: 'datetime',
      enabled: false,
      categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
  },
  yaxis: {
      show: false,
  },
  xaxis: {
      show: false,
      labels: {
          show: false,
      },
      axisTicks: {
          show: false,
      },
  },
  tooltip: {
      x: {
          format: 'dd/MM/yy HH:mm',
          enabled: false,
      },
  },
  colors: [vihoAdminConfig.secondary],
  responsive: [
    {
      breakpoint:1365,
      options: {
          chart: {
              height: 220
          }
      },
  },
    {
      breakpoint: 575,
      options: {
          chart: {
              height: 180
          }
      },
  },
   {
      breakpoint: 992,
      options: {
          chart: {
              height: 250
          }
      },
  }],
};
// var chart21 = new ApexCharts(document.querySelector("#chart-3dash"), options21);
// chart21.render();
//column chart
var options54 = {
  series: [{
      data: [400, 230, 448, 370, 540, 580, 690, 1100, 1200]
  }],
  chart: {
      type: "bar",
      height: 200,
      toolbar: {
          show: false,
      },
  },
  plotOptions: {
      bar: {
          horizontal: false,
          distributed: true,
          columnWidth: '30%',
          startingShape: "rounded",
          endingShape: "rounded",
          colors: {
              backgroundBarColors: ["#e5edef"],
              backgroundBarOpacity: 1,
              backgroundBarRadius: 9
          }
      }
  },
  dataLabels: {
      enabled: false
  },
  grid: {
      yaxis: {
          lines: {
              show: false
          },
      }
  },
  yaxis: {
      labels: {
          formatter: function(val) {
              return val / 100 + "K";
          },
      },
      labels: {
          show: false,
      },
  },
  xaxis: {
      axisBorder: {
          show: false
      },
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Sep", "Oct"],
      labels: {
          show: true,
      },
      axisTicks: {
          show: false,
      },
  },
  colors: [
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary,
      vihoAdminConfig.primary
  ],
  legend: {
      show: false
  }
};
// var chart54 = new ApexCharts(document.querySelector("#chart-unique-2"), options54);
// chart54.render();
