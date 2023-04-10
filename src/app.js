import { h, Component, render } from "/web_modules/preact.js";
import htm from "/web_modules/htm.js";



const html = htm.bind(h);

class App extends Component {
  render() {
    return html`
      <div id="main">
        <div id="fContainer">


          <footer id="info">
            <aside id="copyright">
              <span id="designed"
                >(c) 2020 Created by <a href="https://altaredwood.work">Alta Redwood</a> in Washington state</span>
            </aside>
          </footer>

          

        </div>
        <style>
          :root {
            --uiFonts: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            --contentFonts: "Lato", "Open Sans", "Lucida Grande", "Ubuntu Sans", "Segoe UI", "Roboto", Helvetica, sans-serif;
            --displayFonts: "Gentona", "Baufra", Georgia, serif;
            --monoFonts: "Hack", "Fira Sans", "Anonymous Pro", "Inconsolata", monospace;
            --textFonts: "Calluna", "Caslon", "Garamond", serif;
            --sFonts: Courier, "Open Sans", monospace;
            --condensedFonts: "Densia Sans", "Fira Sans Condensed", "Roboto Condensed", "Open Sans Condensed", "Ubuntu Condensed", sans-serif;
            --white-color: white;
          }
          html {
            box-sizing: border-box;
          }
          body {
            margin-left: 0px;
            margin-right: 0px;
            padding-left: 0px;
            padding-right: 0px;
            background: white;
          }
         
        </style>
      </div>
    `;
  }
}

export default App;
