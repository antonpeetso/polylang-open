const dispatch = wp.data.dispatch;
dispatch( 'core' ).addEntities( [
    {
        name: 'languages', // route name
        kind: 'polylang-open/v1', // namespace
        baseURL: 'polylang-open/v1/languages', // API path without /wp-json
    },
] );