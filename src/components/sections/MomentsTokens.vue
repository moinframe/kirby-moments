<template>
  <k-section :label="label">
    <k-button-group slot="options">
      <k-button icon="add" size="xs" variant="filled" theme="positive" :text="t('moinframe.moments.panel.tokens.add')"
        @click="openCreateDialog" />
    </k-button-group>

    <k-items v-if="tokens.length" :items="items" :layout="'list'" @option="onOption" />

    <k-empty v-else icon="key" @click="openCreateDialog">
      {{ t('moinframe.moments.panel.tokens.empty') }}
    </k-empty>
  </k-section>
</template>

<script>
export default {
  data() {
    return {
      label: "",
      tokens: [],
    };
  },
  computed: {
    items() {
      return this.tokens.map((token) => ({
        text: token.name,
        info: token.created,
        icon: { type: "key", back: "black" },
        options: [
          {
            text: this.t("delete"),
            icon: "trash",
            click: () => this.openDeleteDialog(token.id),
          },
        ],
      }));
    },
  },
  async created() {
    await this.loadTokens();
  },
  methods: {
    async loadTokens() {
      const response = await this.load();
      this.label = response.label;
      this.tokens = response.tokens || [];
    },
    t(key) {
      return this.$t(key);
    },
    openCreateDialog() {
      this.$panel.dialog.open({
        component: "k-form-dialog",
        props: {
          fields: {
            name: {
              label: this.$t("moinframe.moments.panel.tokens.name"),
              type: "text",
              required: true,
              placeholder: this.$t(
                "moinframe.moments.panel.tokens.name.placeholder"
              ),
            },
          },
          submitButton: this.$t("moinframe.moments.panel.tokens.create"),
        },
        on: {
          submit: async (values) => {
            try {
              const response = await this.$api.post("moments/tokens", {
                name: values.name,
              });

              this.$panel.dialog.close();
              await this.loadTokens();

              this.$panel.dialog.open({
                component: "k-form-dialog",
                props: {
                  fields: {
                    info: {
                      type: "info",
                      text: this.$t("moinframe.moments.panel.tokens.created.info"),
                    },
                    token: {
                      label: "Token",
                      type: "text",
                      disabled: true,
                      font: "monospace",
                    },
                  },
                  value: {
                    token: response.token,
                  },
                  submitButton: false,
                  cancelButton: this.$t("close"),
                },
              });
            } catch (e) {
              this.$panel.notification.error(
                e.message || "Failed to create token"
              );
            }
          },
        },
      });
    },
    openDeleteDialog(tokenId) {
      this.$panel.dialog.open({
        component: "k-remove-dialog",
        props: {
          text: this.$t("moinframe.moments.panel.tokens.delete.confirm"),
        },
        on: {
          submit: async () => {
            try {
              await this.$api.delete("moments/tokens/" + tokenId);
              this.$panel.dialog.close();
              this.$panel.notification.success(
                this.$t("moinframe.moments.panel.tokens.deleted")
              );
              await this.loadTokens();
            } catch (e) {
              this.$panel.notification.error(
                e.message || "Failed to delete token"
              );
            }
          },
        },
      });
    },
    onOption(option) {
      if (option.click) {
        option.click();
      }
    },
  },
};
</script>
