import 'package:flutter_test/flutter_test.dart';
import 'package:eldera/eldera.dart';

void main() {
  testWidgets('ElderaApp widget test', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(const ElderaApp());

    // Verify that the app title is displayed
    expect(find.text('Eldera Health'), findsOneWidget);
    
    // Verify that the bottom navigation bar is present
    expect(find.text('HOME'), findsOneWidget);
    expect(find.text('NOTIFICATION'), findsOneWidget);
    expect(find.text('SETTINGS'), findsOneWidget);
    expect(find.text('SCHEDULE'), findsOneWidget);
  });

  testWidgets('Bottom navigation works', (WidgetTester tester) async {
    await tester.pumpWidget(const ElderaApp());

    // Tap on the notifications tab
    await tester.tap(find.text('NOTIFICATION'));
    await tester.pumpAndSettle();

    // Verify we're on the notifications screen
    expect(find.text('Notifications'), findsOneWidget);

    // Tap on the settings tab
    await tester.tap(find.text('SETTINGS'));
    await tester.pumpAndSettle();

    // Verify we're on the settings screen
    expect(find.text('Settings'), findsOneWidget);
  });
}
