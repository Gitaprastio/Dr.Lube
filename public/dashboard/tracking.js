angular.module('appModule', ['stepperModule']);

// Stepper Module ready to be included
// ===================================

angular.module('stepperModule', ['ngAnimate', 'ui.bootstrap'])
  .directive('stepperDirective', stepperDirective);

function stepperController($scope) {
  $scope.forms = {
    "steps": [{
      step: 1,
      name: "7 Agustus 2019",
      // template: 'This is the first step',
      // template: "template.step1.html", // Load an html file. Ideally with Angular template cache. If you do so you have to replace {{step.template}} with ng-include="step.template" in directive -> template: ....
      expanded: false
    }, {
      step: 2,
      name: "9 Agustus 2019",
      // template: "This is the second step",
      // template: "template.step2.html", // Load an html file. Ideally with Angular template cache. If you do so you have to replace {{step.template}} with ng-include="step.template" in directive -> template: ....
      expanded: false
    }, {
      step: 3,
      name: "11 Agustus 2019",
      // template: "This is the third step",
      // template: "template.step3.html", // Load an html file. Ideally with Angular template cache. If you do so you have to replace {{step.template}} with ng-include="step.template" in directive -> template: ....
      expanded: false
    }, {
      step: 3,
      name: "15 Agustus 2019",
      // template: "This is the third step",
      // template: "template.step3.html", // Load an html file. Ideally with Angular template cache. If you do so you have to replace {{step.template}} with ng-include="step.template" in directive -> template: ....
      expanded: false
    }, {
      step: 4,
      name: "19 Agustus 2019",
      // template: "This is the fourth step",
      // template: "template.step4.html", // Load an html file. Ideally with Angular template cache. If you do so you have to replace {{step.template}} with ng-include="step.template" in directive -> template: ....
      expanded: false
    }]
  }
}

function stepperDirective() {

  function link($scope, $element, $attrs) {
    $scope.toggleListItems = function(index) {

      $scope.forms.steps[index].expanded = !$scope.forms.steps[index].expanded;

      for (var i = 0; i < $scope.forms.steps.length; i++) {

        if ($scope.forms.steps[i].expanded === true && i != index) {
          $scope.forms.steps[i].expanded = false;
        }
      }
    };
  }

  var directive = {
    restrict: 'E',
    scope: {},
    controller: stepperController,
    template: "<div class=\"stepper\">\n" +
      "<div class=\"step\" ng-repeat=\"step in forms.steps\">\n" + 
      "<div class=\"step-heading\" ng-class=\"{\"active\":!step.expanded}\" ng-click=\"toggleListItems($index)\">\n" +
      "<div class=\"circle\">{{step.step}}</div>\n" +
      "<div class=\"title\">{{step.name}}</div>\n" +
      "</div>" +
      "<div class=\"line\" ng-hide=\"step.step >=forms.steps.length\">\n" +
      "</div>\n" +
      "<div uib-collapse=\"!step.expanded\">\n" +
      "<div class=\"body\">\n" +
      "<div>{{step.template}}</div>\n" +
      "Progress : " +
      "<div></div>\n" +
      "Time : " + 
      "<div></div>\n" +
      "Information : " +
      "</div>\n" +
      "</div>\n" +
      "</div>\n" +
      "</div>",
    link: link
  };
  return directive;
}