// Minimal agent lifecycle template used by tools/agent-manager.js
// This stub provides `activate` and `deactivate` hooks so lifecycle calls do not fail.
module.exports = {
  async activate({ agent }) {
    console.log(`agent-template: activate called for ${agent}`);
  },
  async deactivate({ agent }) {
    console.log(`agent-template: deactivate called for ${agent}`);
  }
};
