<template>
  <div
    class="alert alert-success"
    role="alert"
  >
    {{ success }}
  </div>
</template>

<script>

export default {
    name: "SuccessMessage",
    props: {
        success: {
            type: String,
            required: true
        }
    },
}
</script>