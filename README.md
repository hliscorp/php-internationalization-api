# Internationalization & Localization

This API is a simple wrapper over PO/MO internationalization method, aimed at surmounting noted disadvantages and automating every aspect that is in developer's power to make it work easily:

- Encapsulates internationalization settings required to read from / write to MO files later on, via Lucinda\Internationalization\Settings class.
- Makes possible for GETTEXT utility to locate then read from relevant MO translation file later on based on settings above, via Lucinda\Internationalization\Reader class.
- Locates and writes to PO translation files based on settings above, then compiles them into MO files GETTEXT utility will read from later on, via Lucinda\Internationalization\Writer class.

Choice of this internationalization method on behalf of others wasn't implementation simplicity (as below usage steps definitely prove it's cumbersome to install) but highest efficiency (once installed, it guarantees unrivaled translation speed and memory efficiency). After all, we are not developing applications for ourselves, but for end users who appreciate instant responses!

More information here:<br/>
http://www.lucinda-framework.com/internationalization
