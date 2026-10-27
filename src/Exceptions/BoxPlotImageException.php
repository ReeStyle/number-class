<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class BoxPlotImageException extends BaseRuntimeException
{
    protected const string DEFAULT_MESSAGE = 'The GD extension is required to render box plot images.';
}
