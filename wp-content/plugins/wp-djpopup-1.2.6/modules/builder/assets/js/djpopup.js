class DJPopup {

    constructor(element, options) {

        const self = this;

        self.container = element;
        self.oppened = false;
        if (options.triggers.length) {
            document.addEventListener("DOMContentLoaded", function (event) {
                options.triggers.forEach(function (trigger) {
                    switch (trigger.type) {
                        case 'on_page_load':
                            if (!self.oppened) {
                                var delay = parseInt(trigger.props.delay);
                                setTimeout(
                                    function () {
                                        self.openModal();
                                    }, delay);

                            }
                            break;
                        case 'on_scroll':

                            var distance = parseInt(trigger.props.distance);

                            var body = document.body,
                                html = document.documentElement;

                            var height = Math.max(body.scrollHeight, body.offsetHeight,
                                html.clientHeight, html.scrollHeight, html.offsetHeight);

                            document.addEventListener('scroll', function (e) {
                                if (!self.oppened) {
                                    var currentScroll = parseInt(window.scrollY + screen.height);
                                    var documentHeight = document.body.clientHeight;

                                    var percentageScroll = (currentScroll / documentHeight) * 100;
                                    if (percentageScroll >= distance) {
                                        self.openModal();
                                    }
                                }
                            });

                            break;
                        case 'scroll_to':

                            var targets = self.get_targets(trigger.props);
                            if (targets.length) {
                                targets.forEach(function (target) {
                                    trigger.oppened = false;
                                    var distanceFromTop = parseInt(target.offsetTop);
                                    document.addEventListener('scroll', function (e) {
                                        if (!trigger.oppened) {
                                            let documentHeight = document.body.scrollHeight;
                                            let currentScroll = parseInt(window.scrollY + window.innerHeight);
                                            if (currentScroll >= distanceFromTop) {
                                                self.openModal(trigger);
                                            }
                                        }


                                    });
                                });

                            }

                            break;
                        case 'on_click':
                            self.bind_triggers(trigger, 'click');
                            break;
                        case 'on_hover':
                            self.bind_triggers(trigger, 'mouseover');
                            break;
                    }
                });
            });

        }

    }

    is_repeat(props) {
        const self = this;

        return ((typeof props.display_multiple !== 'undefined') && props.display_multiple);
    }

    get_targets(props) {
        const self = this;
        let targets = [];
        if (typeof props.link !== 'undefined' && props.link !== '') {
            targets =  document.querySelectorAll('[href="' + props.link + '"]');
        } else if (typeof props.selector !== 'undefined' && props.selector !== '') {
            targets = document.querySelectorAll(props.selector);
        }


        return targets;
    }

    bind_triggers(trigger, event) {
        const self = this;

        var targets = self.get_targets(trigger.props);
        if (targets.length) {
            targets.forEach(function (target) {
                target.removeAttribute('uk-scroll');
                target.addEventListener(event, function (e) {
                    if (!self.oppened || self.is_repeat(trigger.props)) {
                        self.openModal();
                    }
                    e.preventDefault();
                    return false;
                });
            })
        }


    }

    static init(element, options) {
        var defaultSettings = {
            triggers: []
        }
        Object.assign(defaultSettings, options);

        var popup = new DJPopup(element, options);


    }

    openModal(trigger) {
        const self = this;
        UIkit.modal(self.container).show();
        self.oppened = true;
        if (typeof trigger !== 'undefined') {
            trigger.oppened = true;
        }
    }

}