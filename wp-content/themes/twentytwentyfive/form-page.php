<?php
/*
* Template Name: Form Page
*/
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo single_post_title(); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="loader-container"><div class="loader"></div></div>

<div id="page">
    <main id="custom-form-container" class="wp-block-group px-0 px-md-4">
        <h1 class="text-center mb-5">Form Page</h1>
        <form id="custom-form" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>">
            <input type="hidden" id="nonce" name="nonce" value="<?php echo wp_create_nonce('custom_form_nonce'); ?>">

            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="Fname">First name:</label>
                    <input type="text" class="form-control mb-2" id="Fname" name="Fname" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="Lname">Last name:</label>
                    <input type="text" class="form-control mb-2" id="Lname" name="Lname" required>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="mobile">Mobile phone:</label>
                    <input type="tel" class="form-control mb-2" id="mobile" name="mobile">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="telephone">Telephone/Fax:</label>
                    <input type="tel" class="form-control mb-2" id="telephone" name="telephone">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="email">Email:</label>
                    <input type="email" class="form-control mb-2" id="email" name="email">
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="dob">Date of birth:</label>
                    <input type="date" class="form-control mb-2" id="dob" name="dob">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="county">County:</label>
                    <input type="text" class="form-control mb-2" id="county" name="county">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="country">Country:</label>
                    <input type="text" class="form-control mb-2" id="country" name="country">
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="city">City:</label>
                    <input type="text" class="form-control mb-2" id="city" name="city">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="address">Address:</label>
                    <input type="text" class="form-control mb-2" id="address" name="address">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="post_code">Post code:</label>
                    <input type="text" class="form-control mb-2" id="post_code" name="post_code">
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="lead_source">Lead source:</label>
                    <select class="form-select mb-2" id="lead_source" name="lead_source">
                        <option value="" selected>None</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Google">Google</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="salutation">Salutation:</label>
                    <select class="form-select mb-2" id="salutation" name="salutation">
                        <option value="" selected>None</option>
                        <option value="Mr">Mr</option>
                        <option value="Miss">Miss</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <label class="form-label" for="treatment_interest">Treatment interest:</label>
                    <textarea class="form-control mb-2" id="treatment_interest" name="treatment_interest"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <fieldset class="mt-2">
                        <legend>Preferences:</legend>
                        <label class="form-label w-100"><input type="checkbox" id="opt_email" name="opt_email" value="1"> Subscribe to email reminders & notifications</label>
                        <label class="form-label w-100"><input type="checkbox" id="opt_letter" name="opt_letter" value="1"> Subscribe to letters</label>
                        <label class="form-label w-100"><input type="checkbox" id="opt_sms" name="opt_sms" value="1"> Subscribe to SMS</label>
                        <label class="form-label w-100"><input type="checkbox" id="opt_newsletter" name="opt_newsletter" value="1"> Subscribe to newsletters</label>
                        <label class="form-label w-100"><input type="checkbox" id="opt_phone" name="opt_phone" value="1"> Subscribe to phone calls</label>
                    </fieldset>
                </div>
            </div>
            
            <div id="form-response" class="text-center my-2"></div>

            <div class="row mt-4 mb-2">
                <div class="col-12">
                    <button class="btn w-100 btn-outline-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </main>

    <script>
    document.getElementById('custom-form').addEventListener('submit', function(event) {
        event.preventDefault();

        let form = this;

        event.submitter.disabled = true;

        document.documentElement.classList.add('loading');
        
        let formData = new FormData(form);
        formData.append('action', 'custom_form_submit');

        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let formResponse = document.getElementById('form-response');
            formResponse.textContent = data.data.message;
            formResponse.classList.remove('text-success');
            formResponse.classList.remove('text-danger');
            if (data.success) {
                formResponse.classList.add('text-success');
            }
            else {
                formResponse.classList.add('text-danger');
            }
            document.documentElement.classList.remove('loading');
            event.submitter.disabled = false;
            if (data.success) {
                form.reset();
            }
        });
    });
    </script>

    <footer id="footer" class="wp-block-group site-footer text-center mt-4">
        <div class="wp-block-group alignwide">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
        </div>
        <?php wp_footer(); ?>
    </footer>
</div>

</body>
</html>
