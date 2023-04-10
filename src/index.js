import { h, Component, render } from "/web_modules/preact.js";
import htm from "/web_modules/htm.js";

import App from "./app.js";
import Hammerhead from "./hammerhead.js";

import Footer from "./footer.js";

const html = htm.bind(h);

class Index extends Component {
  render() {
    return html`
      <${App} />
      <${Hammerhead} animal="Mango the Quaker Parrot" />
      <${Footer} />
    `;
  }
}

render(
  html`
    <${Index} />
  `,
  document.getElementById("main")
);
