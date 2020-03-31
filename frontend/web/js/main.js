(function () {
    'use strict';
    window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('js-needs-validation');
        var validation = Array.prototype.filter.call(forms, function (form) {
            let ruleValidate = JSON.parse(form.getAttribute('data-validate-rules'));
            form.formValidator = new FormValidator(form, ruleValidate);
            form.addEventListener('submit', function (event) {
                let resultValidate = form.formValidator.validate();
                if (!resultValidate) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            }, false);
        });

        var elementList = document.querySelectorAll('[data-confirm]');
        for (let item of elementList) {
            item.addEventListener('click', function (e) {
                let textConfirm = this.getAttribute('data-confirm');
                if (!confirm(textConfirm)) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                }
            }, false);
        }

        var postLinks = document.querySelectorAll('[data-method=post]');
        for (let postLink of postLinks) {
            postLink.addEventListener('click', function (e) {
                let link = this.href;
                let form = document.createElement('form');
                form.method = 'post';
                form.action = link;

                var metaCsrf = document.querySelector('[type="csrf"]');
                let csrfToken = metaCsrf.content;
                let csrfName = metaCsrf.name;
                let hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = csrfName;
                hiddenField.value = csrfToken;

                form.appendChild(hiddenField);

                document.body.appendChild(form);
                form.submit();

                e.preventDefault();
            })
        }
    }, false);

})();

class FormValidator {
    errors = {};
    validated = [];

    constructor(form, rules) {
        this.form = form;
        this.rules = rules;
    }

    validate() {
        this.errors = {};

        for (var index in this.rules) {
            this.rules[index].forEach(function (currentValue, key, array) {
                if (typeof this[currentValue[1]] != "undefined") {
                    let input = this.form[index];
                    input.classList.remove('is-valid');
                    input.classList.remove('is-invalid');
                    this[currentValue[1]](input, index, currentValue, currentValue['params']);

                    if (!this.wasValidated(index))
                        this.validated.push(index);
                }
            }, this);

            let input = this.form[index];
            if (this.wasValidated(index)) {
                if (this.hasError(index)) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            }
        }
        this.renderError();
        return Object.keys(this.errors).length == 0;
    }

    vRequire(input, nameInput, rule, params) {
        let value = input.value;
        if (value == '') {
            this.addError(nameInput, rule['message']);
            return false;
        } else {
            return true;
        }
    }

    vLength(input, nameInput, rule, params) {
        let value = input.value;
        if (value !== '' && value.length > params['max']) {
            this.addError(nameInput, rule['message']['max']);
            return false;
        }
        if (value !== '' && value.length < params['min']) {
            this.addError(nameInput, rule['message']['min']);
            return false;
        }
        return true;
    }

    vCompare(input, nameInput, rule, params) {
        let value = input.value;
        let compareInput = this.form[rule['compareAttribute']];
        if (value != compareInput.value) {
            this.addError(nameInput, rule['message']);
            return false;
        }
        return true;
    }

    vEmail(input, nameInput, rule, params) {
        let value = input.value;
        if (value !== '' && !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
            this.addError(nameInput, rule['message']);
            return false;
        }
        return true;
    }

    /*vDate(input, nameInput, rule, params){
        let value = input.value;
        if (!/^\d\d\d\d-\d\d-\d\d&/.test(value)) {
            this.addError(nameInput, rule['message']);
            return false;
        }
        return true;
    }*/

    addError(nameInput, message) {
        if (typeof this.errors[nameInput] == "undefined")
            this.errors[nameInput] = [];

        this.errors[nameInput].push(message);
    }

    renderError() {
        for (let index in this.errors) {
            let input = this.form[index];
            input.classList.add('is-invalid');
            let errorBlock = input.parentElement.querySelector(".invalid-feedback");
            if (errorBlock !== null) {
                errorBlock.innerHTML = this.errors[index].join('<br>');
            }
        }
    }

    hasError(index) {
        return typeof this.errors[index] != "undefined";
    }

    wasValidated(index) {
        return this.validated.indexOf(index) !== -1;
    }
}
