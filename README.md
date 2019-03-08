# MinimalEditor

Inserts a _Preview_ checkbox attached to `<textarea id="message">` elements that shows MyCode output rendered by the server-side MyBB Parser.

The minimal preview mode works as a substitute to the visual editor, which can be toggled with the _Show the MyCode formatting options on the posting pages_ preference (**UserCP → Edit Options**).

Parsing with HTML is currently not supported.

**Requirements:**
- MyBB 1.8.x
- https://github.com/frostschutz/MyBB-PluginLibrary
- PHP >= 7.1
