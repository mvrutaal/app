/**
 * YJSlide - image slider V3
 * @version		3.0
 * @MooTools version 1.1
 * Copyright Youjoomla LLC
 * @author	Constantin Boiangiu
 */


var YJSlide = new Class({
    initialize: function (A) {
        this.options = Object.extend({
            outerContainer: null,
            innerContainer: null,
            elements: null,
            navigation: {
                forward: null,
                back: null
            },
            slideType: 0,
            orientation: 1,
            slideTime: 3000,
            duration: 600,
            tooltips: 1,
            autoslide: 1,
            navInfo: null,
            navLinks: null
        }, A || {});
        this.start()
    },
    start: function () {
        this.currentElement = 0;
        this.direction = 1;
        this.elements = $(this.options.innerContainer).getElements(this.options.elements);
        this.showEffect = {};
        this.hideEffect = {};
        this.firstRun = {};
        if (this.options.slideType !== 0) {
            if (this.options.orientation == 1) {
                this.showEffect.left = [1200, 0];
                this.hideEffect.left = [0, 1200];
                this.firstRun.left = 1200
            } else {
                this.showEffect.top = [400, 0];
                this.hideEffect.top = [0, 400];
                this.firstRun.top = 400
            }
        }
        if (this.options.slideType !== 1) {
            this.showEffect.opacity = [0, 1];
            this.hideEffect.opacity = [1, 0];
            this.firstRun.opacity = 0
        }
        this.elements.each(function (B, A) {
            B.setStyles({
                display: "block",
                position: "absolute",
                top: 0,
                left: 0,
                "z-index": (100 - A)
            });
            if (this.options.slideType !== 1 && A !== this.currentElement) {
                B.setStyle("opacity", 0)
            }
            this.elements[A]["fx"] = new Fx.Styles(B, {
                wait: false,
                duration: this.options.duration
            });
            if (A !== this.currentElement) {
                this.elements[A]["fx"].set(this.firstRun)
            }
            B.addEvent("mouseover", function (C) {
                if ($defined(this.period)) {
                    $clear(this.period)
                }
            }.bind(this));
            B.addEvent("mouseout", function (C) {
                if (this.options.autoslide == 0) {
                    this.period = this.rotateSlides.periodical(this.options.slideTime, this)
                }
            }.bind(this))
        }.bind(this));
        if (!this.options.tooltips) {
            new Tips($$(".YJS_link"), {
                className: "YJS_tips"
            })
        }
        if (!this.options.autoslide) {
            this.period = this.rotateSlides.periodical(this.options.slideTime, this)
        }
        this.setNavigation();
        if (this.options.navLinks) {
            this.secondNavigation()
        }
    },
    rotateSlides: function () {
        var A = this.currentElement + this.direction;
        if (A < 0) {
            A = this.elements.length - 1
        }
        if (A > this.elements.length - 1) {
            A = 0
        }
        this.nextSlide(A)
    },
    nextSlide: function (A) {
        if (A == this.currentElement) {
            return
        }
        this.elements[this.currentElement]["fx"].start(this.hideEffect);
        this.elements[A]["fx"].start(this.showEffect);
        this.currentElement = A;
        if ($(this.options.navInfo)) {
            $(this.options.navInfo).setHTML("Link " + (A + 1) + " of " + this.elements.length)
        }
    },
    setNavigation: function () {
        if (!$(this.options.navigation.forward)) {
            return
        }
        $(this.options.navigation.forward).addEvent("click", function (A) {
            new Event(A).stop();
            this.direction = 1;
            this.resetAutoslide();
            this.rotateSlides()
        }.bind(this));
        $(this.options.navigation.back).addEvent("click", function (A) {
            new Event(A).stop();
            this.direction = -1;
            this.resetAutoslide();
            this.rotateSlides()
        }.bind(this))
    },
    resetAutoslide: function () {
        if ($defined(this.period)) {
            $clear(this.period);
            this.period = this.rotateSlides.periodical(this.options.slideTime, this)
        }
    },
    secondNavigation: function () {
        this.navElements = $$(this.options.navLinks);
        this.navElements.each(function (B, A) {
            B.addEvent("click", function (C) {
                new Event(C).stop();
                this.resetAutoslide();
                this.nextSlide(A)
            }.bind(this))
        }.bind(this));
        if (!this.options.tooltips) {
            new Tips(this.navElements, {
                className: "YJS_tips"
            })
        }
    }
});