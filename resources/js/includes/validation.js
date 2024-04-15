import {
    Form as VeeForm,
    Field as VeeField,
    defineRule,
    ErrorMessage,
    configure,
} from "vee-validate";
import {
    required,
    min,
    max,
    alpha_spaces as alphaSpaces,
    email,
    min_value as minVal,
    max_value as maxVal,
    confirmed,
    not_one_of as excluded,
} from "@vee-validate/rules";

export default {
    install(app) {
        app.component("VeeForm", VeeForm);
        app.component("VeeField", VeeField);
        app.component("ErrorMessage", ErrorMessage);

        defineRule("required", required); //form required
        defineRule("min", min); // min lenght
        defineRule("max", max); // max lenght
        defineRule("alphaSpaces", alphaSpaces); // only letters and spaces
        defineRule("email", email); // valid email
        defineRule("minVal", minVal); // min num value
        defineRule("maxVal", maxVal); // max num value
        defineRule("passwordMismatch", confirmed); // two fields matches
        defineRule("excluded", excluded); // not allowed choice
        defineRule("countryExcluded", excluded); // not allowed choice

        configure({
            generateMessage: (ctx) => {
                const messages = {
                    required: `Pole ${ctx.field} je povinné.`,
                    min: `Obsah ${ctx.field} je moc krátký.`,
                    max: `The field ${ctx.field} is too long.`,
                    alphaSpaces: `The field ${ctx.field} may only contain alphabetical characters and spaces.`,
                    email: `The field ${ctx.field} must be a valid email.`,
                    minVal: `Hodnota ${ctx.field} je příliš nízká.`,
                    maxVal: `Hodnota ${ctx.field} je příliš vysoká.`,
                    excluded: `You are not allowed to use this value for the field ${ctx.field}.`,
                    countryExcluded: `Due to restrctions, we do not accept users from this location.`,
                    passwordMismatch: `The passwords dont match.`,
                    tos: `You must accept the Terms of Service.`,
                };
                const message = messages[ctx.rule.name]
                    ? messages[ctx.rule.name]
                    : `The field ${ctx.field} is invalid.`;

                return message;
            },
            validateOnBlur: true,
            validateOnChange: true,
            validateOnInput: false,
            validateOnModelUpdate: true,
        });
    },
};
