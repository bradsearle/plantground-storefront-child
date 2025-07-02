module.exports = {
  extends: ['stylelint-config-standard-scss'],
  rules: {
    'at-rule-no-unknown': [
      true,
      {
        ignoreAtRules: ['use', 'forward'], // add more if you use Tailwind etc
      },
    ],
  },
};
