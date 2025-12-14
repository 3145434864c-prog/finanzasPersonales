# TODO: Fix Negative monto_gastado Error in Presupuesto

## Completed Tasks
- [x] Analyze the error log showing negative monto_gastado (-175000.0) after updating a Movimiento
- [x] Identify root cause: Incremental updates assume original monto was added to presupuesto, but if movimiento predates presupuesto, it wasn't
- [x] Fix created event: Recalculate monto_gastado by summing all movimientos instead of adding the new one
- [x] Fix updated event: Recalculate both original and new presupuesto by summing movimientos instead of adjusting difference
- [x] Fix deleted event: Recalculate monto_gastado by summing remaining movimientos instead of subtracting the deleted one

## Remaining Tasks
- [ ] Test the fix by creating/updating/deleting movimientos and verifying presupuesto monto_gastado is accurate
- [ ] Clear existing logs and monitor for new errors
- [ ] If needed, run a script to recalculate all presupuesto monto_gastado based on current movimientos
