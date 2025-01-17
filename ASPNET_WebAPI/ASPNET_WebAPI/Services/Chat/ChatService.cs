using LLama.Common;
using LLama;

using System.Text;
using static LLama.LLamaTransforms;
using ASPNET_WebAPI.Services.ChatServices.Loader;

namespace ASPNET_WebAPI.Services.ChatServices
{

    public class ChatService
    {
        private readonly LLamaContext _context;
        private readonly ChatSession _session;
        private readonly SessionState _state;

        public ChatService(LlamaContextLoader contextLoader)
        {
            _context = contextLoader.Context;

            // TODO: replace with a stateless executor
            _session = new ChatSession(new InteractiveExecutor(_context))
                        .WithOutputTransform(new KeywordTextOutputStreamTransform(["User:", "User: ", "Assistant:"], redundancyLength: 8))
                        .WithHistoryTransform(new HistoryTransform());

            _state = _session.GetSessionState();
        }

        public async Task<string> SendAsync(ChatHistory history)
        {
            // This is a stateless service instantiated as Singletons.
            // So Clear the history before each call.
            // But this might cause issues if the service is used in a multi-threaded environment / multiple ppl.
            // TODO : Implement a way to handle multiple users.
            // (when make context as singleton and session as scoped, will cause error 'NoKvSlot' )
            _session.LoadSession(_state);

            var result = _session.ChatAsync(
               history,
               new InferenceParams()
               {
                   AntiPrompts = ["User:"],
               });

            var sb = new StringBuilder();
            await foreach (var r in result)
            {
                Console.Write(r);
                sb.Append(r);
            }

            return sb.ToString();
        }

        public async IAsyncEnumerable<string> SendStream(ChatHistory history)
        {
            _session.LoadSession(_state);

            var results = _session.ChatAsync(
                history,
                new InferenceParams() { AntiPrompts = ["User:"], }
            );

            Console.WriteLine("\n\nResponse : ");
            await foreach (var output in results)
            {
                Console.Write(output);
                yield return output;
            }
        }
    }

    public class HistoryTransform : DefaultHistoryTransform
    {
        public override string HistoryToText(ChatHistory history)
        {
            return base.HistoryToText(history) + "\n Assistant:";
        }

    }
}
