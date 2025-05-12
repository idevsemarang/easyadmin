@php
    $preffix_method = isset($method) ? $method . '_' : '';
    $passFormId = (isset($field['name']) ? $field['name'] : 'id_' . $key);
    $passFormId = $preffix_method . $passFormId;
@endphp

<div class="{{ isset($field['class']) ? $field['class'] : 'form-group' }}">
    <label>{{ isset($field['label']) ? $field['label'] : 'Label ' . $key }}</label>
    <div class="input-group">
        <input type="password" id="{{ $passFormId }}"
            name="{{ isset($field['name']) ? $field['name'] : 'name_' . $key }}"
            value="{{ isset($field['value']) ? $field['value'] : '' }}" class="form-control idev-form" autocomplete="off">
        <button type="button" id="sh_{{$passFormId}}" class="btn btn-outline-secondary toggle-password" data-target="{{ $passFormId }}">
            Show
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('sh_{{$passFormId}}');
        btn.addEventListener('click', function() {
            const input = document.getElementById(this.getAttribute('data-target'));
            console.log(input);

            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Hide';
            } else {
                input.type = 'password';
                this.textContent = 'Show';
            }
        });
    });
</script>
