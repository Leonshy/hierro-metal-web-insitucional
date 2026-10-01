<?php

namespace App\Http\Controllers;

use App\Actions\Forms\StoreFormSubmission;
use App\Http\Requests\ContactFormRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Controller fino — la lógica va en Actions (CLAUDE.md §7).
 * Rate limiting via `throttle` en routes/web.php — ausente en IPG (docs/01 §A.3).
 */
class FormSubmissionController extends Controller
{
    public function contact(ContactFormRequest $request, StoreFormSubmission $action): RedirectResponse
    {
        $result = $action->handle('contacto', $request->validated(), $request->ip(), $request);

        return back()
            ->with('status', 'Gracias por escribirnos. Te vamos a responder a la brevedad.')
            ->with('meta_event_id', $result['meta_event_id']);
    }
}
