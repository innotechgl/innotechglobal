(function (angular) {
    'use strict';

    var POPOVER_SHOW = 'popoverToggleShow';
    var POPOVER_HIDE = 'popoverToggleHide';

    var module = angular.module('popoverToggle', ['ui.bootstrap']);

    module.config(['$tooltipProvider', function ($tooltipProvider) {
        var triggers = {};
        triggers[POPOVER_SHOW] = POPOVER_HIDE;

        $tooltipProvider.setTriggers(triggers);
    }]);

    module.directive('popoverToggle', ['$rootScope', '$timeout', '$window', function ($rootScope, $timeout, $window) {
        return {
            restrict: 'A',
            link: link
        };

        function link($scope, $element, $attrs) {
            $attrs.popoverTrigger = POPOVER_SHOW;

            // To check if we are clicking an element with a popover class
            // http://stackoverflow.com/questions/16863917/check-if-class-exists-somewhere-in-parent-vanilla-js
            var hasSomeParentTheClass = function hasSomeParentTheClass(element, classname) {
                if (element.className && element.className.split(' ').indexOf(classname) >= 0) return true;
                return element.parentNode && hasSomeParentTheClass(element.parentNode, classname);
            }

            // To hide the popover and initialise values again.
            var hidePopover = function hidePopover() {
                $scope.open = false;
                if ($window.onclick) {
                    $window.onclick = null;
                }
                $element.triggerHandler(POPOVER_HIDE);
                $scope.$apply(); //--> trigger digest cycle and make angular aware. 
            }

            $scope.$watch('open', function (newValue) {
                $timeout(function () {
                    if (newValue) {
                        $element.triggerHandler(POPOVER_SHOW);

                        $window.onclick = function ($event) {
                            var clickedElement = $event.target;
                            if (!clickedElement) return; // Element exists

                            var clickedOnTheElement = hasSomeParentTheClass(clickedElement, 'popover'); // Element parent has popover class

                            if (!clickedOnTheElement) {
                                hidePopover();
                                return;
                            }
                        };

                    } else {
                        hidePopover();
                    }
                });
            })
        }
    }]);

})(angular);