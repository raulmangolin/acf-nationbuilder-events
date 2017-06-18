<?php

class acf_field_nationbuilder_events extends acf_field_select
{
    // vars
    var $settings,
        $defaults;

    function __construct()
    {
        // vars
        $this->name = 'nationbuilder_events';
        $this->label = "Events";
        $this->category = __("NationBuilder", 'acf');
        $this->defaults = array(
            'access_token' => '',
            'slug' => '',
            'domain' => ''
        );

        acf_field::__construct();

        // settings
        $this->settings = array(
            'path' => apply_filters('acf/helpers/get_path', __FILE__),
            'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
            'version' => '0.1.0'
        );

    }

    function create_options($field)
    {
        $key = $field['name'];

        ?>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label>NationBuilder Access Token</label>
                <p class="description"><a href="https://nationbuilder.com/admin/oauth/test_tokens" target="_blank">https://nationbuilder.com/admin/oauth/test_tokens</a></p>
            </td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'text',
                    'name' => 'fields[' . $key . '][access_token]',
                    'value' => $field['access_token']
                ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label>NationBuilder Nation Domain</label>
                <p class="description">https://<strong>example</strong>.nationbuilder.com</p>
            </td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'text',
                    'name' => 'fields[' . $key . '][domain]',
                    'value' => $field['domain']
                ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label>NationBuilder Site Slug</label>
                <p class="description">If you have only one nation, this value is the same as above<br>
                    https://<strong>example</strong>.nationbuilder.com</p>
            </td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'text',
                    'name' => 'fields[' . $key . '][slug]',
                    'value' => $field['slug']
                ));
                ?>
            </td>
        </tr>
        <?php

    }

    function getOption($key)
    {
        return isset($this->options[$key]) ? $this->options[$key] : null;
    }

    function create_field($field)
    {
        $data = json_decode($field['value']);

        $events = $this->get_nb_events($field);
        ?>
        <div class="acf-text clearfix acf-nationbuilder_events" data-field-id="<?php echo $field['key'] ?>">
            <select id="<?php echo $field['id'] ?>" class="post_object" name="<?php echo $field['name'] ?>">
                <option value=""></option>
                <?php foreach ($events->results as $ev): ?>
                    <option value="<?php echo $ev->id; ?>" <?php echo ($data == $ev->id) ? 'selected="selected"' : ''; ?>'><?php echo $ev->name; ?> - <?php echo $ev->venue->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    function get_nb_events($field)
    {
        $nbDomain = 'https://' . $field['domain'] . '.nationbuilder.com/api/v1/';
        $nbToken = $field['access_token'];
        $nbSlug = $field['slug'];

        $url = $nbDomain . 'sites/' . $nbSlug . '/pages/events?limit=1000&access_token=' . $nbToken;

        $data = $this->getData($url);
        if ($data['code'] == 200) {
            return json_decode($data['body']);
        } else {
            return array();
        }
    }

    function getData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = trim(curl_exec($ch));
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);

        curl_close($ch);

        return array('body' => $body, 'code' => $httpCode);
    }

    function update_value($value, $post_id, $field)
    {
        // array?
        if (is_array($value) && isset($value['id'])) {
            $value = $value['id'];
        }

        // object?
        if (is_object($value) && isset($value->ID)) {
            $value = $value->ID;
        }

        return $value;
    }
}


// create field
new acf_field_nationbuilder_events();