<template>
  <h1>hello user {{ id }}</h1>
</template>
<script>
    export default {
        name: "Editcategory",
        props: {
            id: {
                type: [String, Number],
                required: true,
            }
        },
    }
</script>