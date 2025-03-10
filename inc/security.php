<?php
/**
 * Filters the default value for security options.
 *
 * @param mixed  $default_value  The default value to return if the option does not exist
 *                               in the database.
 * @param string $option         Option name.
 * @param bool   $passed_default Was `get_option()` passed a default value?
 */
function security_checks_on( $default_value, $option, $passed_default ) {
    $default_value = 'on';
    return $default_value;
}
add_filter( 'default_option_seravo-disable-xml-rpc-all-methods', __NAMESPACE__ . '\security_checks_on', 100, 3 );
add_filter( 'default_option_seravo-disable-json-user-enumeration', __NAMESPACE__ . '\security_checks_on', 100, 3 );
add_filter( 'default_option_seravo-disable-get-author-enumeration', __NAMESPACE__ . '\security_checks_on', 100, 3 );
