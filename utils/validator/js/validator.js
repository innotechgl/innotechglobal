var validate_class = new Class({
    initialize: function (initial_errors) {
        // Set initial errors
        this.errors = initial_errors;
        // Load requested validation fields
        this.requested_validation_fields = $$('.requested');
        // Display errors
        this.display_errors();
    },
    display_errors: function () {
        this.requested_validation_fields.each(function (item) {
            try {
                // check if element exists in errors
                if (this.errors[0][$(item).get('name')] !== undefined) {
                    // Create element
                    var el = new Element('span', {
                        'class': 'error_field',
                        'html': '<br />' + this.errors[0][$(item).get('name')]
                    }, this);
                    //inject element
                    el.inject($(item), 'after');
                }
            }
            catch (error) {
                console.log(error)
            }
        }, this);
    }
});