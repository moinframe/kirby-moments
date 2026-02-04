---
title: Javascript
---

This plugin comes with a light javascript implementation that is **totally optional**.

The scripts do two things:
1. Convert the timestamp to a human readable date like "two minutes ago" if the moment is not older than 3 days.
2. Add a lightbox with keyboard controls. You can use the Arrow keys to navigate back and forth and the Esc key to go back to the grid.

You can remove the scripts by setting `'moinframe.moments.lightbox' => false` in your `config.php`. See the [configuration](/docs/moinframe-moments/02-configuration) for more information.
