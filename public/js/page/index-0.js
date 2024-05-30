"use strict";

var statistics_chart = document.getElementById("myChart").getContext('2d');

// var myChart = new Chart(statistics_chart, {
//   type: 'line',
//   data: {
//     labels: ["A", "B", "C","D", "E", "F", "G"],
//     datasets: [{
//       label: 'Statistics',
//       data: [1,2,3,4,5,6,7],
//       borderWidth: 5,
//       borderColor: '#6777ef',
//       backgroundColor: 'transparent',
//       pointBackgroundColor: '#fff',
//       pointBorderColor: '#6777ef',
//       pointRadius: 4
//     }]
//   },
//   options: {
//     legend: {
//       display: false
//     },
//     scales: {
//       yAxes: [{
//         gridLines: {
//           display: false,
//           drawBorder: false,
//         },
//         ticks: {
//           stepSize: 150
//         }
//       }],
//       xAxes: [{
//         gridLines: {
//           color: '#fbfbfb',
//           lineWidth: 2
//         }
//       }]
//     },
//   }
// });


var resultsJson = document.getElementById('attendanceChartData').value;
var results = JSON.parse(resultsJson);

var labels = results.map(function(result) {
  return result.date;
});

var data = results.map(function(result) {
  return result.count;
});

var myChart = new Chart(statistics_chart, {
  type: 'line',
  data: {
      labels: labels,
      datasets: [{
          label: 'Total User Come Late / Back Early',
          data: data,
          borderWidth: 5,
          borderColor: '#6777ef',
          backgroundColor: 'transparent',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#6777ef',
          pointRadius: 4
      }]
  },
  options: {
      legend: {
          display: false
      },
      scales: {
          yAxes: [{
              gridLines: {
                  display: false,
                  drawBorder: false,
              },
              ticks: {
                  stepSize: 1,
                  suggestedMin: 0
              }
          }],
          xAxes: [{
              gridLines: {
                  color: '#fbfbfb',
                  lineWidth: 2
              }
          }]
      },
  }
});